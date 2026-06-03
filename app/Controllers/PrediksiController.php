<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class PrediksiController extends Controller
{
    public function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct();
        }
        Auth::requireLogin();
    }

    /**
     * GET /prediksi
     * Mengarahkan tampilan berdasarkan role yang sedang login
     */
    public function index(): void
    {
        $db = Database::getInstance();
        $role = $_SESSION['role'] ?? 'user';
        
        if ($role === 'admin') {
            // Jalur Admin: Melihat semua tanaman aktif seluruh user
            $result = $db->query("SELECT * FROM tanaman WHERE status = 'aktif' ORDER BY id DESC");
            $allTanamanUser = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $allTanamanUser[] = $row;
                }
            }
            $this->view('prediksi.index', [
                'pageTitle' => 'Pusat Analisis Prediksi Panen',
                'allTanamanUser' => $allTanamanUser
            ]);
        } else {
            // Jalur User: Hanya melihat tanaman aktif milik dirinya sendiri
            $userId = (int)$_SESSION['user_id'];
            $result = $db->query("SELECT * FROM tanaman WHERE user_id = $userId AND status = 'aktif' ORDER BY id DESC");
            
            $tanamanMilikUser = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $tanamanMilikUser[] = $row;
                }
            }
            $this->view('prediksi.user.prediksi', [
                'pageTitle' => 'Estimasi & Prediksi Panen Anda',
                'tanamanMilikUser' => $tanamanMilikUser
            ]);
        }
    }

    /**
     * POST /prediksi
     * Hanya boleh dieksekusi oleh Admin untuk kalibrasi data
     */
    public function store(): void
    {
        if (($_SESSION['role'] ?? 'user') !== 'admin') {
            $this->redirect('dashboard');
            return;
        }

        $db = Database::getInstance();
        $tanamanId       = (int)($_POST['tanaman_id'] ?? 0);
        $estimasiPanen   = (int)($_POST['estimasi_panen'] ?? 0);
        $suhuIdeal       = $_POST['suhu_ideal'] !== '' ? (float)$_POST['suhu_ideal'] : null;
        $phIdeal         = $_POST['ph_ideal'] !== '' ? (float)$_POST['ph_ideal'] : null;
        $kelembabanIdeal = $_POST['kelembaban_ideal'] !== '' ? (float)$_POST['kelembaban_ideal'] : null;
        $catatanAdmin    = isset($_POST['catatan_admin']) ? htmlspecialchars(strip_tags(trim($_POST['catatan_admin']))) : '';

        if ($tanamanId <= 0 || $estimasiPanen <= 0) {
            $this->redirect('prediksi');
            return;
        }

        // Ambil data tanggal tanam untuk hitung ulang tanggal prediksi pas
        $resTanaman = $db->query("SELECT tanggal_tanam, user_id, nama_tanaman FROM tanaman WHERE id = $tanamanId");
        if (!$resTanaman || $resTanaman->num_rows === 0) {
            $this->redirect('prediksi');
            return;
        }
        $tanaman = $resTanaman->fetch_assoc();
        
        $tanggalTanam = new \DateTime($tanaman['tanggal_tanam']);
        $tanggalPrediksiPanen = (clone $tanggalTanam)->modify("+$estimasiPanen days")->format('Y-m-d');

        $valSuhu = is_null($suhuIdeal) ? "NULL" : $suhuIdeal;
        $valPh = is_null($phIdeal) ? "NULL" : $phIdeal;
        $valKelembaban = is_null($kelembabanIdeal) ? "NULL" : $kelembabanIdeal;
        $valCatatan = $catatanAdmin !== '' ? "'$catatanAdmin'" : "NULL";

        // Update database tanaman
        $db->query("UPDATE tanaman SET 
                    estimasi_panen = $estimasiPanen,
                    tanggal_prediksi_panen = '$tanggalPrediksiPanen',
                    suhu_ideal = $valSuhu,
                    ph_ideal = $valPh,
                    kelembaban_ideal = $valKelembaban,
                    catatan_admin = $valCatatan 
                    WHERE id = $tanamanId");

        // Kirim Notifikasi Real-time ke User Terkait
        $targetUser = $tanaman['user_id'];
        $namaTanaman = $tanaman['nama_tanaman'];
        $pesanNotif = "Admin memberikan rekomendasi iklim baru & kalibrasi data untuk tanaman " . $namaTanaman . ". Silakan cek menu Prediksi Panen.";
        
        $db->query("INSERT INTO notifikasi (user_id, pesan, is_read, created_at) VALUES ($targetUser, '$pesanNotif', 0, NOW())");

        $this->redirect('prediksi');
    }
}