<?php
// app/Helpers/functions.php — Fungsi bantu global

use App\Core\Auth;
use App\Core\Database;

/**
 * Sanitasi input.
 */
function clean(string $data): string
{
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Format tanggal ke bahasa Indonesia.
 */
function formatTanggal(string $date): string
{
    $bulan = [
        '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $d = explode('-', $date);
    return $d[2] . ' ' . $bulan[(int)$d[1]] . ' ' . $d[0];
}

/**
 * Hitung sisa hari hingga panen.
 * Positif = sisa hari, negatif = sudah terlewat.
 */
function sisaHariPanen(string $tanggalTanam, int $estimasiHari): int
{
    $tanam    = new DateTime($tanggalTanam);
    $panen    = (clone $tanam)->modify("+{$estimasiHari} days");
    $today    = new DateTime();
    $interval = $today->diff($panen);
    return $interval->invert ? -$interval->days : $interval->days;
}

/**
 * Hitung progress pertumbuhan tanaman dalam persen (0–100).
 */
function progressTanaman($tanggal_tanam, $estimasi_panen)
{
    // PROTEKSI: Jika estimasi panen masih 0 atau belum diatur admin, progress langsung 0% (tidak crash)
    if (empty($estimasi_panen) || $estimasi_panen <= 0) {
        return 0; 
    }

    $tgl_tanam = new DateTime($tanggal_tanam);
    $tgl_sekarang = new DateTime();
    
    // Hitung berapa hari yang sudah dilewati sejak menanam
    $selisih = $tgl_sekarang->diff($tgl_tanam)->days;
    
    // Jika tanggal menanam justru di masa depan (salah input)
    if ($tgl_sekarang < $tgl_tanam) {
        return 0;
    }

    // Hitung persentase progres pertumbuhan
    $persentase = round(($selisih / $estimasi_panen) * 100);

    // Batasi agar maksimal persentase hanya sampai 100%
    return $persentase > 100 ? 100 : $persentase;
}

/**
 * Hitung jumlah notifikasi belum dibaca milik user yang sedang login.
 */
function countUnreadNotif(): int
{
    if (!App\Core\Auth::isLoggedIn()) return 0;
    $db  = App\Core\Database::getInstance();
    $uid = App\Core\Auth::id();
    
    // Menghitung langsung dari tabel konsultasi yang statusnya masih NULL (belum dijawab admin)
    $query = "SELECT COUNT(*) as total FROM konsultasi WHERE user_id = $uid AND status IS NULL";
    $res = $db->query($query);
    
    if ($res) {
        // Antisipasi jika menggunakan driver PDO
        if (method_exists($res, 'fetch')) {
            $row = $res->fetch(\PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        }
        // Antisipasi jika menggunakan driver MySQLi
        if (method_exists($res, 'fetch_assoc')) {
            $row = $res->fetch_assoc();
            return (int)($row['total'] ?? 0);
        }
    }
    return 0;
}

/**
 * Generate URL asset relatif terhadap public/.
 */
function asset(string $path): string
{
    return BASE_URL . ltrim($path, '/');
}

/**
 * Generate URL relatif terhadap BASE_URL.
 */
/**
 * Menghindari duplikasi deklarasi fungsi url jika sudah ada di core framework
 */
if (!function_exists('url')) {
    function url(string $path): string
    {
        return BASE_URL . ltrim($path, '/');
    }
}

/**
 * Escape HTML untuk output yang aman.
 */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Ambil flash message dan hapus dari session.
 */
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}