<?php
// app/Controllers/KonsultasiController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Konsultasi;
use App\Models\Notifikasi;

class KonsultasiController extends Controller
{
    private Konsultasi $model;
    private Notifikasi $notifModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->model      = new Konsultasi();
        $this->notifModel = new Notifikasi();
    }

    // GET /konsultasi
    public function index(): void
    {
        $uid      = Auth::id();
        $detailId = (int)$this->query('detail', 0);
        $detail   = null;
        $balasan  = [];

        if ($detailId) {
            $detail  = $this->model->getDetailByUser($detailId, $uid);
            if ($detail) {
                $balasan = $this->model->getBalasan($detailId);
            }
        }

        $this->view('konsultasi.index', [
            'pageTitle'   => 'Konsultasi Ahli',
            'flash'       => $this->getFlash(),
            'konsultasi'  => $this->model->getByUser($uid),
            'detail'      => $detail,
            'balasan'     => $balasan,
            'detailId'    => $detailId,
        ]);
    }

    // POST /konsultasi
    public function handle(): void
    {
        $action = $_POST['action'] ?? '';
        match ($action) {
            'buat'  => $this->buat(),
            'balas' => $this->balas(),
            default => $this->redirect('konsultasi'),
        };
    }

    private function buat(): void
    {
        $uid   = Auth::id();
        $judul = $this->input('judul');
        $pesan = $this->input('pesan');
        $this->model->create($uid, $judul, $pesan);
        $this->notifModel->notifToAllAdmins('Konsultasi Baru',
            "Ada pertanyaan baru dari user: $judul");
        $this->flash('success', 'Pertanyaan berhasil dikirim ke admin!');
        $this->redirect('konsultasi');
    }

    private function balas(): void
    {
        $uid = Auth::id();
        $kid = (int)$_POST['konsultasi_id'];

        $this->model->addBalasan($kid, $uid, $this->input('pesan'));
        $this->model->updateStatus($kid, 'menunggu');

        // Kirim notif ke semua admin bahwa user membalas di room lama
        $konsul = $this->model->getDetail($kid);
        if ($konsul) {
            $namaUser = Auth::user()['nama'];
            $this->notifModel->notifToAllAdmins(
                'Balasan Konsultasi',
                "$namaUser membalas konsultasi: \"{$konsul['judul']}\"",
                'konsultasi'
            );
        }

        $this->flash('success', 'Balasan terkirim.');
        $this->redirect('konsultasi?detail=' . $kid);
    }
}