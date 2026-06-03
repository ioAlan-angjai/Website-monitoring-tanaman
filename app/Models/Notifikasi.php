<?php
// app/Models/Notifikasi.php

namespace App\Models;

use App\Core\Model;

class Notifikasi extends Model
{
    protected string $table = 'notifikasi';

    public function getByUser(int $userId): array
    {
        $result = $this->query(
            "SELECT * FROM notifikasi WHERE user_id=$userId ORDER BY created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTerbaru(int $userId, int $limit = 4): array
    {
        $result = $this->query(
            "SELECT * FROM notifikasi WHERE user_id=$userId ORDER BY created_at DESC LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countUnread(int $userId): int
    {
        $res = $this->query(
            "SELECT COUNT(*) c FROM notifikasi WHERE user_id=$userId AND is_read=0"
        );
        return (int)$res->fetch_assoc()['c'];
    }

    public function create(int $userId, string $judul, string $pesan, string $tipe = 'sistem'): bool
    {
        $stmt = $this->prepare(
            "INSERT INTO notifikasi (user_id, judul, pesan, tipe) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('isss', $userId, $judul, $pesan, $tipe);
        return $stmt->execute();
    }

    public function notifToAllAdmins(string $judul, string $pesan, string $tipe = 'konsultasi'): bool
    {
        $this->query(
            "INSERT INTO notifikasi (user_id, judul, pesan, tipe)
             SELECT id, '$judul', '$pesan', '$tipe' FROM users WHERE role='admin'"
        );
        return true;
    }

    public function markRead(int $id, int $userId): bool
    {
        $stmt = $this->prepare("UPDATE notifikasi SET is_read=1 WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }

    public function markAllRead(int $userId): bool
    {
        $stmt = $this->prepare("UPDATE notifikasi SET is_read=1 WHERE user_id=?");
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }

    public function hapus(int $id, int $userId): bool
    {
        $stmt = $this->prepare("DELETE FROM notifikasi WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }
}