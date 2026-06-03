<?php
// app/Controllers/PerkembanganController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Perkembangan;
use App\Models\Tanaman;

class PerkembanganController extends Controller
{
    private Perkembangan $model;
    private Tanaman      $tanamanModel;

    public function __construct()
    {
        // Memastikan pengguna sudah login
        Auth::requireLogin();
        $this->model        = new Perkembangan();
        $this->tanamanModel = new Tanaman();
    }

    /**
     * GET /perkembangan
     * Menampilkan log perkembangan tanaman
     */
    public function index(): void
    {
        $uid  = Auth::id();
        $role = $_SESSION['role'] ?? 'user';
        
        // Menangkap ID tanaman dari URL query string (?tanaman_id=...)
        $tanamanId = isset($_GET['tanaman_id']) ? (int)$_GET['tanaman_id'] : null;

        // PERBAIKAN LOGIKA: Bedakan data yang ditarik antara Admin dan User
        if ($role === 'admin') {
            // Admin memantau seluruh perkembangan tanaman yang ada di sistem
            // Menggunakan getAll() atau jika belum ada gunakan getByUser() sebagai fallback
            $riwayat = method_exists($this->model, 'getAll') ? $this->model->getAll() : $this->model->getByUser($uid);
            $tanamanDropdown = []; // Admin tidak butuh dropdown karena form di-hide
        } else {
            // User hanya melihat data perkembangan miliknya sendiri
            $riwayat = $this->model->getByUser($uid);
            $tanamanDropdown = $this->tanamanModel->getForDropdown($uid);
        }

        // Jika tanaman_id dikirimkan via URL, filter riwayat log hanya untuk tanaman tersebut
        if ($tanamanId) {
            $riwayat = array_filter($riwayat, function($item) use ($tanamanId) {
                return (int)$item['tanaman_id'] === $tanamanId;
            });
        }

        $this->view('perkembangan.index', [
            'pageTitle'       => 'Log Perkembangan',
            'riwayat'         => $riwayat,
            'tanamanDropdown' => $tanamanDropdown,
            'selectedTanaman' => $tanamanId, // Digunakan di view untuk auto-select dropdown form
            'flash'           => $this->getFlash(),
        ]);
    }

    /**
     * POST /perkembangan
     * Memproses penyimpanan log perkembangan dari form user
     */
    public function store(): void
    {
        // REVISI KUNCI: Tolak akses jika yang mencoba mengirim data POST adalah Admin
        if (($_SESSION['role'] ?? 'user') === 'admin') {
            $this->flash('danger', 'Admin hanya bertugas memantau, tidak diizinkan menambah data perkembangan.');
            $this->redirect('perkembangan');
            return;
        }

        $tanamanId = (int)$_POST['tanaman_id'];

        // Jalankan proses simpan jika yang mengirim data adalah benar-benar User
        $this->model->create([
            'tanaman_id' => $tanamanId,
            'tanggal'    => $this->input('tanggal'),
            'tinggi_cm'  => (float)($_POST['tinggi_cm'] ?? 0),
            'kondisi'    => $this->input('kondisi'),
            'catatan'    => $this->input('catatan'),
        ]);

        // REVISI TERBARU: Set tanda ada_laporan_baru menjadi 1 di database agar terdeteksi oleh admin
        try {
            $db = Database::getInstance();
            $db->query("UPDATE tanaman SET ada_laporan_baru = 1 WHERE id = $tanamanId");
        } catch (\Exception $e) {
            // Pengaman pasif jika kolom database belum siap migrasi
        }

        $this->flash('success', 'Log perkembangan tanaman Anda berhasil disimpan!');
        $this->redirect('perkembangan');
    }
}