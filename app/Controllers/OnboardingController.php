<?php
// app/Controllers/OnboardingController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Tanaman;
use App\Models\Notifikasi;
use App\Models\Jadwal;

class OnboardingController extends Controller
{
    private Tanaman    $tanamanModel;
    private Notifikasi $notifModel;
    private Jadwal     $jadwalModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->tanamanModel = new Tanaman();
        $this->notifModel   = new Notifikasi();
        $this->jadwalModel  = new Jadwal();
    }

    // GET /onboarding
    public function index(): void
    {
        $uid = Auth::id();
        if ($this->tanamanModel->hasAny($uid)) {
            $this->redirect('dashboard');
        }
        $this->view('onboarding.index', ['error' => '']);
    }

    // POST /onboarding
    public function store(): void
    {
        $uid  = Auth::id();
        $nama = $this->input('nama_tanaman');
        $tgl  = $this->input('tanggal_tanam');
        $est  = (int)($_POST['estimasi_panen'] ?? 0);

        if (!$nama || !$tgl || !$est) {
            $this->view('onboarding.index', [
                'error' => 'Nama tanaman, tanggal tanam, dan estimasi panen wajib diisi.'
            ]);
            return;
        }

        $data = [
            'user_id'      => $uid,
            'nama_tanaman' => $nama,
            'jenis'        => $this->input('jenis'),
            'tanggal_tanam'=> $tgl,
            'estimasi_panen' => $est,
            'lokasi'       => $this->input('lokasi'),
            'catatan'      => $this->input('catatan'),
            'suhu'         => !empty($_POST['suhu'])        ? (int)$_POST['suhu']        : null,
            'ph_tanah'     => !empty($_POST['ph_tanah'])    ? (float)$_POST['ph_tanah']  : null,
            'kelembapan'   => !empty($_POST['kelembapan'])  ? (int)$_POST['kelembapan']  : null,
            'jenis_pupuk'  => !empty($_POST['jenis_pupuk']) ? $this->input('jenis_pupuk'): null,
        ];

        $tid = $this->tanamanModel->create($data);
        $this->notifModel->create($uid, 'Selamat Datang! 🌱',
            "Tanaman pertamamu \"$nama\" berhasil ditambahkan. Selamat berkebun!");
        $this->jadwalModel->createDefault($tid, $uid);

        unset($_SESSION['new_user']);
        $this->flash('success', "Selamat datang! Tanaman \"$nama\" berhasil ditambahkan. Kebunmu siap dipantau! 🌱");
        $this->redirect('dashboard');
    }
}