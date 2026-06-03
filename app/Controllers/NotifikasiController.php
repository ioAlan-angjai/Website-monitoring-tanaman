<?php
// app/Controllers/NotifikasiController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    private Notifikasi $model;

    public function __construct()
    {
        Auth::requireLogin();
        $this->model = new Notifikasi();
    }

    // GET /notifikasi atau /admin/notifikasi
    public function index(): void
    {
        $uid = Auth::id();

        // Deteksi secara dinamis apakah request datang dari URL admin atau bukan
        $fullUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $isRedirectToAdmin = (strpos($fullUri, '/admin/notifikasi') !== false);
        $redirectUrl = $isRedirectToAdmin ? 'admin/notifikasi' : 'notifikasi';

        // Aksi via query string (tetap GET untuk UX sederhana)
        if (isset($_GET['baca_semua'])) {
            $this->model->markAllRead($uid);
            $this->flash('success', 'Semua notifikasi ditandai telah dibaca.');
            $this->redirect($redirectUrl);
        }
        if (isset($_GET['baca'])) {
            $this->model->markRead((int)$_GET['baca'], $uid);
            $this->redirect($redirectUrl);
        }
        if (isset($_GET['hapus'])) {
            $this->model->hapus((int)$_GET['hapus'], $uid);
            $this->flash('info', 'Notifikasi dihapus.');
            $this->redirect($redirectUrl);
        }

        // --- AMBIL JUMLAH NOTIFIKASI YANG BELUM DIBACA ---
        // Kita hitung jumlah data yang is_read = 0 (atau false)
        $allNotif = $this->model->getByUser($uid);
        $unreadCount = 0;
        foreach ($allNotif as $n) {
            if (!(bool)$n['is_read']) {
                $unreadCount++;
            }
        }

        $this->view('notifikasi.index', [
            'pageTitle'   => 'Pusat Notifikasi',
            'flash'       => $this->getFlash(),
            'notifikasi'  => $allNotif,
            'unreadNotif' => $unreadCount, // <-- INI YANG MENYALAKAN TITIK MERAH DI HEADER!
        ]);
    }
}