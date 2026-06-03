<?php
// public/index.php — Entry point (Front Controller)

define('ROOT_PATH', dirname(__DIR__) . '/');
define('APP_PATH',  ROOT_PATH . 'app/');

// Load konfigurasi
require ROOT_PATH . 'config/config.php';

// Autoloader sederhana — namespace App\ → app/
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
        $file     = APP_PATH . $relative . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Load helper functions
require APP_PATH . 'Helpers/functions.php';

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Routes ────────────────────────────────────────────────────────────────
use App\Core\Router;

$router = new Router();

// Auth
$router->get('/auth/login',    'AuthController@loginForm');
$router->post('/auth/login',   'AuthController@login');
$router->get('/auth/register', 'AuthController@registerForm');
$router->post('/auth/register','AuthController@register');
$router->get('/auth/logout',   'AuthController@logout');

// Onboarding
$router->get('/onboarding',    'OnboardingController@index');
$router->post('/onboarding',   'OnboardingController@store');

// Dashboard
$router->get('/dashboard',     'DashboardController@index');
$router->get('/',              'DashboardController@index');

// Tanaman
$router->get('/tanaman',       'TanamanController@index');
$router->post('/tanaman',      'TanamanController@handle');

// Jadwal
$router->get('/jadwal',        'JadwalController@index');
$router->post('/jadwal',       'JadwalController@handle');

// Perkembangan
$router->get('/perkembangan',  'PerkembanganController@index');
$router->post('/perkembangan', 'PerkembanganController@store');


// Prediksi
$router->get('/prediksi',  'PrediksiController@index');
$router->post('/prediksi', 'PrediksiController@store');
// Rute ketika tombol "Simpan & Kirim" di modal diklik (POST)
$router->post('prediksi', 'PrediksiController@store');

// Notifikasi
$router->get('/notifikasi',    'NotifikasiController@index');
$router->get('/admin/notifikasi', 'NotifikasiController@index');

// Konsultasi (user)
$router->get('/konsultasi',    'KonsultasiController@index');
$router->post('/konsultasi',   'KonsultasiController@handle');

// Profil
$router->get('/profil',        'ProfilController@index');
$router->post('/profil',       'ProfilController@update');

// Admin — konsultasi
$router->get('/admin/konsultasi',  'AdminController@konsultasi');
$router->post('/admin/konsultasi', 'AdminController@handleKonsultasi');


// Dispatch
$router->dispatch();