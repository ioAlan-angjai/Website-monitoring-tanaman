<?php
// app/Core/Controller.php — Base class untuk semua Controller

namespace App\Core;

abstract class Controller
{
    /**
     * Render view dengan data yang diberikan.
     * Semua key array $data menjadi variabel di dalam view.
     *
     * @param string $view   Path relatif terhadap app/Views/ (gunakan dot, misal 'auth.login')
     * @param array  $data   Data yang dioper ke view
     */
    protected function view(string $view, array $data = []): void
    {
        // Konversi 'auth.login' → 'app/Views/auth/login.php'
        $path = APP_PATH . 'Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($path)) {
            throw new \RuntimeException("View tidak ditemukan: {$path}");
        }

        // Pastikan semua variabel umum selalu terdefinisi
        // array_merge: nilai dari $data akan menimpa default jika ada
        $data = array_merge([
            'flash'              => null,
            'pageTitle'          => '',
            'filter'             => 'semua',
            'detail'             => null,
            'detailId'           => 0,
            'balasan'            => [],
            'tanaman'            => [],
            'tanamanAktif'       => [],
            'tanamanDropdown'    => [],
            'jadwal'             => [],
            'jadwalHari'         => [],
            'konsultasi'         => [],
            'notifikasi'         => [],
            'notifTerbaru'       => [],
            'riwayat'            => [],
            'user'               => null,
            'error'              => '',
            'totalTanaman'       => 0,
            'jadwalHariIni'      => 0,
            'tanamPanen'         => 0,
            'unread'             => 0,
        ], $data);

        // Ekstrak data sebagai variabel
        extract($data);

        require $path;
    }

    /**
     * Redirect ke URL relatif terhadap BASE_URL.
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit;
    }

    /**
     * Simpan flash message ke session.
     */
    protected function flash(string $type, string $msg): void
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    /**
     * Ambil flash message lalu hapus dari session.
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Ambil data POST yang sudah di-sanitasi.
     */
    protected function input(string $key, mixed $default = ''): string
    {
        $val = $_POST[$key] ?? $default;
        return htmlspecialchars(strip_tags(trim((string)$val)));
    }

    /**
     * Cek apakah request method adalah POST.
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Ambil query string GET yang sudah di-sanitasi.
     */
    protected function query(string $key, mixed $default = ''): string
    {
        $val = $_GET[$key] ?? $default;
        return htmlspecialchars(strip_tags(trim((string)$val)));
    }

    /**
     * Return JSON response (untuk endpoint AJAX).
     */
    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}