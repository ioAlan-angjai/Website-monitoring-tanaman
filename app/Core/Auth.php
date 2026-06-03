<?php
// app/Core/Auth.php — Helper autentikasi & otorisasi

namespace App\Core;

class Auth
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): array
    {
        return [
            'id'    => $_SESSION['user_id'] ?? 0,
            'nama'  => $_SESSION['nama']    ?? '',
            'email' => $_SESSION['email']   ?? '',
            'role'  => $_SESSION['role']    ?? 'user',
            'foto'  => $_SESSION['foto']    ?? null,
        ];
    }

    public static function id(): int
    {
        return (int)($_SESSION['user_id'] ?? 0);
    }

    public static function role(): string
    {
        return $_SESSION['role'] ?? 'user';
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    /**
     * Paksa login — redirect ke halaman login jika belum masuk.
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    /**
     * Paksa admin — redirect ke dashboard jika bukan admin.
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
    }

    /**
     * Paksa user sudah punya tanaman — redirect ke onboarding jika belum.
     */
    public static function requireTanaman(): void
    {
        self::requireLogin();
        $db  = Database::getInstance();
        $uid = self::id();
        $res = $db->query("SELECT COUNT(*) c FROM tanaman WHERE user_id=$uid");
        if (!(bool)$res->fetch_assoc()['c']) {
            header('Location: ' . BASE_URL . 'onboarding');
            exit;
        }
    }

    /**
     * Login: simpan data user ke session.
     */
    public static function loginUser(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['foto']    = $user['foto'] ?? null;
    }

    /**
     * Logout: hapus session.
     */
    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}