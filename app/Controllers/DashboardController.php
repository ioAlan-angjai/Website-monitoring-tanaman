<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Tanaman;
use App\Models\Jadwal;
use App\Models\Notifikasi;

class DashboardController extends Controller
{
    // GET / dan GET /dashboard
    public function index(): void
    {
        Auth::requireLogin(); 
        
        $uid = Auth::id();
        $role = $_SESSION['role'] ?? 'user'; 

        $tanamanModel = new Tanaman();
        $jadwalModel  = new Jadwal();
        $notifModel   = new Notifikasi();

        if ($role === 'admin') {
            // --- LOGIKA UTAMA DASHBOARD ADMIN ---
            
            // Memanggil fungsi baru yang aman dari dalam model Tanaman
            $allTanamanUser = $tanamanModel->getAllTanamanAdmin();

            $data = [
                'pageTitle'      => 'Dashboard Admin',
                'flash'          => $this->getFlash(),
                'allTanamanUser' => $allTanamanUser,
                'totalTanaman'   => count($allTanamanUser),
                'totalUserAktif' => $this->countUniqueUsers($allTanamanUser),
            ];

            $this->view('dashboard.admin', $data);

        } else {
            // --- LOGIKA UTAMA DASHBOARD USER/TENANT ---
            $data = [
                'pageTitle'     => 'Dashboard',
                'flash'         => $this->getFlash(),
                'totalTanaman'  => $tanamanModel->countByStatus($uid, 'aktif'),
                'jadwalHariIni' => $jadwalModel->countHariIni($uid),
                'tanamPanen'    => $tanamanModel->countByStatus($uid, 'panen'),
                'unread'        => countUnreadNotif(),
                'tanamanAktif'  => $tanamanModel->getAktifByUser($uid, 6),
                'jadwalHari'    => $jadwalModel->getHariIni($uid, 5),
                'notifTerbaru'  => $notifModel->getTerbaru($uid, 4),
            ];

            $this->view('dashboard.index', $data);
        }
    }

    private function countUniqueUsers(array $tanaman): int
    {
        $users = [];
        foreach ($tanaman as $t) {
            $userId = $t['user_id'] ?? $t['uid'] ?? null;
            if ($userId) {
                $users[$userId] = true;
            }
        }
        return count($users);
    }
}