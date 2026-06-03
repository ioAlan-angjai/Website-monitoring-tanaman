<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // GET /auth/login
    public function loginForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        $this->view('auth.login', ['error' => '']);
    }

    // POST /auth/login
    public function login(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $email    = $this->input('email');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->view('auth.login', ['error' => 'Semua field wajib diisi.']);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            Auth::loginUser($user);
            $this->redirect('dashboard');
        } else {
            $this->view('auth.login', ['error' => 'Email atau password salah.']);
        }
    }

    // GET /auth/register
    public function registerForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        $this->view('auth.register', ['error' => '']);
    }

    // POST /auth/register
    public function register(): void
    {
        $nama     = $this->input('nama');
        $email    = $this->input('email');
        $password = $_POST['password'] ?? '';
        $konfirm  = $_POST['konfirm']  ?? '';

        if (!$nama || !$email || !$password) {
            $this->view('auth.register', ['error' => 'Semua field wajib diisi.']);
            return;
        }
        if ($password !== $konfirm) {
            $this->view('auth.register', ['error' => 'Password tidak cocok.']);
            return;
        }
        if (strlen($password) < 6) {
            $this->view('auth.register', ['error' => 'Password minimal 6 karakter.']);
            return;
        }
        if ($this->userModel->emailExists($email)) {
            $this->view('auth.register', ['error' => 'Email sudah terdaftar.']);
            return;
        }

        $hash  = password_hash($password, PASSWORD_DEFAULT);
        $newId = $this->userModel->create($nama, $email, $hash);

        // Auto-login setelah registrasi
        Auth::loginUser([
            'id' => $newId, 'nama' => $nama,
            'email' => $email, 'role' => 'user', 'foto' => null
        ]);

        $_SESSION['new_user'] = true;
        $this->redirect('onboarding');
    }

    // GET /auth/logout
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('auth/login');
    }
}