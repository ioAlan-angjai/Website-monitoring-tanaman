<?php
// app/Controllers/JadwalController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Jadwal;
use App\Models\Tanaman;

class JadwalController extends Controller
{
    private Jadwal  $model;
    private Tanaman $tanamanModel;

    public function __construct()
    {
        Auth::requireLogin(); 
        $this->model        = new Jadwal();
        $this->tanamanModel = new Tanaman();
    }

    public function index(): void
    {
        $uid    = Auth::id();
        $role   = $_SESSION['role'] ?? 'user';
        $filter = $this->query('filter', 'semua');

        $this->model->updateTerlewat($uid);

        if ($role === 'admin') {
            $tanamanDropdown = $this->tanamanModel->getAllTanamanAdmin();
            $daftarJadwal    = $this->model->getAllJadwalAdmin($filter);
        } else {
            $tanamanDropdown = $this->tanamanModel->getForDropdown($uid);
            $daftarJadwal    = $this->model->getByUser($uid, $filter);
        }

        $this->view('jadwal.index', [
            'pageTitle'      => 'Jadwal Perawatan',
            'flash'          => $this->getFlash(),
            'jadwal'         => $daftarJadwal,
            'tanamanDropdown'=> $tanamanDropdown,
            'filter'         => $filter,
        ]);
    }

    public function handle(): void
    {
        $action = $_POST['action'] ?? '';
        match ($action) {
            'tambah'  => $this->tambah(),
            'selesai' => $this->selesai(),
            'hapus'   => $this->hapus(),
            default   => $this->redirect('jadwal'),
        };
    }

    private function tambah(): void
    {
        if (($_SESSION['role'] ?? 'user') !== 'admin') {
    $this->flash('danger', 'Hanya admin yang dapat membuat jadwal.');
    $this->redirect('jadwal');
    return;
}
        $uid  = Auth::id();
        $role = $_SESSION['role'] ?? 'user';
        
        $tanamanId = (int)$_POST['tanaman_id'];
        $tanaman   = $this->tanamanModel->find($tanamanId);

        if (!$tanaman) {
            $this->flash('danger', 'Data tanaman tidak ditemukan.');
            $this->redirect('jadwal');
            return;
        }

        $targetUserId = ($role === 'admin') ? $tanaman['user_id'] : $uid;
        $jenisPerawatan = $this->input('jenis_perawatan');
        $tanggalJadwal  = $this->input('tanggal_jadwal');
        $waktu          = $this->input('waktu');

        // Insert ke tabel jadwal via object model
        $this->model->create([
            'tanaman_id'      => $tanamanId,
            'user_id'         => $targetUserId,
            'jenis_perawatan' => $jenisPerawatan,
            'tanggal_jadwal'  => $tanggalJadwal,
            'waktu'           => $waktu,
            'catatan'         => $this->input('catatan') ?? '',
        ]);

        // Kirim Notifikasi jika dilakukan oleh Admin
        if ($role === 'admin') {
            $this->model->kirimNotifAdmin(
                $targetUserId, 
                $jenisPerawatan, 
                $tanaman['nama_tanaman'], 
                $tanggalJadwal, 
                $waktu
            );
            $this->flash('success', 'Jadwal berhasil dibuat dan Notifikasi terkirim ke user!');
        } else {
            $this->flash('success', 'Jadwal berhasil ditambahkan!');
        }

        $this->redirect('jadwal');
    }

    private function selesai(): void
    {
        $id   = (int)$_POST['id'];
        $role = $_SESSION['role'] ?? 'user';
        
        // REVISI: Mengamankan pemanggilan status selesai untuk admin dan user tanpa menabrak protected property
        if ($role === 'admin') {
            // Admin bisa menandai selesai jadwal mana pun tanpa divalidasi user_id-nya
            $this->model->selesaiByAdmin($id); 
        } else {
            $this->model->selesai($id, Auth::id());
        }

        $this->flash('success', 'Jadwal ditandai selesai.');
        $this->redirect('jadwal');
    }

    private function hapus(): void
    {
        $id   = (int)$_POST['id'];
        $role = $_SESSION['role'] ?? 'user';

        // REVISI: Mengamankan proses hapus jadwal untuk admin tanpa bypass/fallback kosong
        if ($role === 'admin') {
            // Admin bisa menghapus jadwal mana pun langsung dari model tanpa divalidasi user_id-nya
            $this->model->hapusByAdmin($id);
        } else {
            $this->model->hapus($id, Auth::id());
        }

        $this->flash('info', 'Jadwal dihapus.');
        $this->redirect('jadwal');
    }
}