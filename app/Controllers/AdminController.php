<?php
// app/Controllers/AdminController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Konsultasi;
use App\Models\Notifikasi;

class AdminController extends Controller
{
    private Konsultasi $konsultasiModel;
    private Notifikasi $notifModel;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->konsultasiModel = new Konsultasi();
        $this->notifModel      = new Notifikasi();
    }

    // GET /admin/konsultasi
    public function konsultasi(): void
    {
        $filter   = $this->query('status', 'semua');
        $detailId = (int)$this->query('detail', 0);
        $detail   = null;
        $balasan  = [];

        if ($detailId) {
            $detail  = $this->konsultasiModel->getDetail($detailId);
            if ($detail) {
                $balasan = $this->konsultasiModel->getBalasan($detailId);
            }
        }

        $this->view('admin.konsultasi', [
            'pageTitle'   => 'Jawab Konsultasi (Admin)',
            'flash'       => $this->getFlash(),
            'konsultasi'  => $this->konsultasiModel->getAll($filter),
            'filter'      => $filter,
            'detail'      => $detail,
            'balasan'     => $balasan,
            'detailId'    => $detailId,
        ]);
    }

    // POST /admin/konsultasi
    public function handleKonsultasi(): void
    {
        $action = $_POST['action'] ?? '';
        match ($action) {
            'balas' => $this->balas(),
            'tutup' => $this->tutup(),
            default => $this->redirect('admin/konsultasi'),
        };
    }

    private function balas(): void
    {
        $kid   = (int)$_POST['konsultasi_id'];
        $uid   = Auth::id();
        $pesan = $this->input('pesan');

        if (!$kid || !$pesan) {
            $this->flash('danger', 'Data tidak valid.');
            $this->redirect("admin/konsultasi?detail=$kid");
        }

        $this->konsultasiModel->addBalasan($kid, $uid, $pesan);
        $this->konsultasiModel->updateStatus($kid, 'dijawab');

        // Notifikasi ke user pemilik konsultasi
        $konsul = $this->konsultasiModel->getDetail($kid);
        if ($konsul) {
            $this->notifModel->create(
                (int)$konsul['user_id'],
                'Konsultasi Dijawab',
                "Admin telah membalas konsultasi Anda: " . $konsul['judul'],
                'konsultasi'
            );
        }

        $this->flash('success', 'Balasan berhasil dikirim!');
        $filter = $_GET['status'] ?? 'semua';
        $this->redirect("admin/konsultasi?status=$filter&detail=$kid");
    }

    private function tutup(): void
    {
        $kid = (int)$_POST['konsultasi_id'];
        $this->konsultasiModel->updateStatus($kid, 'ditutup');
        $this->flash('info', 'Konsultasi berhasil ditutup.');
        $filter = $_GET['status'] ?? 'semua';
        $this->redirect("admin/konsultasi?status=$filter");
    }
}