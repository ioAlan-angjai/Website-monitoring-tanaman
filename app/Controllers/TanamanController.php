<?php
// app/Controllers/TanamanController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Tanaman;
use App\Models\Notifikasi;
use App\Models\Jadwal;

class TanamanController extends Controller
{
    private Tanaman    $model;
    private Notifikasi $notifModel;
    private Jadwal     $jadwalModel;

    public function __construct()
    {
        Auth::requireTanaman();
        $this->model      = new Tanaman();
        $this->notifModel = new Notifikasi();
        $this->jadwalModel = new Jadwal();
    }

    // GET /tanaman
    public function index(): void
    {
        $uid    = Auth::id();
        $filter = $this->query('status', 'all');

        $this->view('tanaman.index', [
            'pageTitle' => 'Manajemen Tanaman',
            'flash'     => $this->getFlash(),
            'tanaman'   => $this->model->getByUser($uid, $filter),
            'filter'    => $filter,
        ]);
    }

    // POST /tanaman — dispatcher berdasarkan action
    public function handle(): void
    {
        $action = $_POST['action'] ?? '';
        match ($action) {
            'tambah'        => $this->tambah(),
            'update_status' => $this->updateStatus(),
            'hapus'         => $this->hapus(),
            default         => $this->redirect('tanaman'),
        };
    }

    private function tambah(): void
    {
        $uid  = Auth::id();
        $nama = $this->input('nama_tanaman');

        $data = [
            'user_id'       => $uid,
            'nama_tanaman'  => $nama,
            'jenis'         => $this->input('jenis'),
            'tanggal_tanam' => $this->input('tanggal_tanam'),
            'estimasi_panen'=> (int)($_POST['estimasi_panen'] ?? 0),
            'lokasi'        => $this->input('lokasi'),
            'catatan'       => $this->input('catatan'),
            'suhu'          => !empty($_POST['suhu'])        ? (int)$_POST['suhu']        : null,
            'ph_tanah'      => !empty($_POST['ph_tanah'])    ? (float)$_POST['ph_tanah']  : null,
            'kelembapan'    => !empty($_POST['kelembapan'])  ? (int)$_POST['kelembapan']  : null,
            'jenis_pupuk'   => !empty($_POST['jenis_pupuk']) ? $this->input('jenis_pupuk'): null,
        ];

        $tid = $this->model->create($data);
        $this->notifModel->create($uid, 'Tanaman Baru',
            "$nama telah ditambahkan ke kebun digitalmu.", 'sistem');
        $this->jadwalModel->createDefault($tid, $uid);

        $this->flash('success', "Tanaman \"$nama\" berhasil ditambahkan!");
        $this->redirect('tanaman');
    }

    private function updateStatus(): void
    {
        $uid    = Auth::id();
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'aktif';
        $this->model->updateStatus($id, $uid, $status);
        $this->flash('success', 'Status tanaman berhasil diperbarui.');
        $this->redirect('tanaman');
    }

    private function hapus(): void
    {
        $uid = Auth::id();
        $id  = (int)($_POST['id'] ?? 0);
        $this->model->deleteByUser($id, $uid);
        $this->flash('info', 'Tanaman telah dihapus dari sistem.');
        $this->redirect('tanaman');
    }
}