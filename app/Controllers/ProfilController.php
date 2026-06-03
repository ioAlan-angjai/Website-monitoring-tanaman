<?php
// app/Controllers/ProfilController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;

class ProfilController extends Controller
{
    private User $model;

    public function __construct()
    {
        Auth::requireLogin();
        $this->model = new User();
    }

    // GET /profil
    public function index(): void
    {
        $user = $this->model->find(Auth::id());
        $this->view('profil.index', [
            'pageTitle' => 'Profil Saya',
            'flash'     => $this->getFlash(),
            'user'      => $user,
        ]);
    }

    // POST /profil
    public function update(): void
    {
        $uid   = Auth::id();
        $user  = $this->model->find($uid);
        $nama  = $this->input('nama');
        $email = $this->input('email');
        $foto  = $user['foto'];

        // Handle upload foto
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === 0) {
            $result = $this->handleUpload($uid, $user['foto']);
            if (isset($result['error'])) {
                $this->view('profil.index', [
                    'pageTitle' => 'Profil Saya',
                    'flash'     => ['type' => 'danger', 'msg' => $result['error']],
                    'user'      => $user,
                ]);
                return;
            }
            $foto = $result['filename'];
        }

        // Simpan perubahan ke database
        $this->model->update($uid, $nama, $email, $foto);
        
        // ================= REVISI TOTAL SESSION =================
        // Perbarui data session global dan session array 'user' secara menyeluruh
        $_SESSION['nama'] = $nama;
        $_SESSION['foto'] = $foto;

        if (isset($_SESSION['user'])) {
            $_SESSION['user']['nama'] = $nama;
            $_SESSION['user']['foto'] = $foto;
            $_SESSION['user']['email'] = $email;
        } else {
            // Jika seandainya class Auth Anda mengandalkan data fresh dari DB,
            // baris ini akan mengamankan struktur array data user di session.
            $_SESSION['user'] = $this->model->find($uid);
        }
        // ========================================================

        // Update password jika diisi
        $newPass = $_POST['new_pass'] ?? '';
        if (!empty($newPass)) {
            $this->model->updatePassword($uid, password_hash($newPass, PASSWORD_DEFAULT));
            if (isset($_SESSION['user'])) {
                $_SESSION['user']['password'] = password_hash($newPass, PASSWORD_DEFAULT);
            }
        }

        $this->flash('success', 'Profil berhasil diperbarui!');
        $this->redirect('profil');
    }

    private function handleUpload(int $uid, ?string $fotoLama): array
    {
        $targetDir = ROOT_PATH . 'public/uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            return ['error' => 'Format foto harus JPG atau PNG.'];
        }

        $filename   = "user_{$uid}_" . time() . ".{$ext}";
        $targetFile = $targetDir . $filename;

        if (!move_uploaded_file($_FILES['foto_profil']['tmp_name'], $targetFile)) {
            return ['error' => 'Gagal memindahkan file.'];
        }

        // Hapus foto lama dari server agar folder tidak penuh sampah file
        if ($fotoLama && file_exists($targetDir . $fotoLama)) {
            unlink($targetDir . $fotoLama);
        }

        return ['filename' => $filename];
    }
}