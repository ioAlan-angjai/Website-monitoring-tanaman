<?php
// app/Core/Router.php — Front-controller router sederhana

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Daftarkan route GET.
     */
    public function get(string $uri, string $controllerAction): void
    {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    /**
     * Daftarkan route POST.
     */
    public function post(string $uri, string $controllerAction): void
    {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    /**
     * Jalankan router: cocokkan URI dan panggil controller@method.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Ambil path saja, buang query string (?action=... atau &tanaman_id=...)
        $fullUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        $uri      = '/' . ltrim(substr($fullUri, strlen($basePath)), '/');
        $uri      = rtrim($uri, '/') ?: '/';

        $routes = $this->routes[$method] ?? [];

        // Jika rute tidak ditemukan, tampilkan pesan 404 yang informatif
        if (!isset($routes[$uri])) {
            http_response_code(404);
            echo '<div style="font-family: sans-serif; text-align: center; margin-top: 50px;">';
            echo '<h1 style="color: #e74c3c;">404 — Halaman tidak ditemukan</h1>';
            echo '<p style="color: #7f8c8d;">Rute untuk metode <strong>' . $method . '</strong> dengan alamat <strong>' . $uri . '</strong> belum terdaftar.</p>';
            echo '<p style="font-size: 13px; color: #95a5a6;">Periksa kembali file pendaftaran rute (routes.php / index.php) Anda.</p>';
            echo '</div>';
            return;
        }

        [$controllerClass, $action] = explode('@', $routes[$uri]);
        $controllerClass = "App\\Controllers\\{$controllerClass}";

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller {$controllerClass} tidak ditemukan.");
        }

        $controller = new $controllerClass();
        $controller->$action();
    }
}