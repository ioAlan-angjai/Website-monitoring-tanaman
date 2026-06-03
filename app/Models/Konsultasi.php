<?php
// app/Models/Konsultasi.php

namespace App\Models;

use App\Core\Model;

class Konsultasi extends Model
{
    protected string $table = 'konsultasi';

    public function getByUser(int $userId): array
    {
        $result = $this->query(
            "SELECT * FROM konsultasi WHERE user_id=$userId ORDER BY created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAll(string $filter = 'semua'): array
    {
        $allowed = ['menunggu', 'dijawab', 'ditutup'];
        $where   = in_array($filter, $allowed) ? "WHERE k.status='$filter'" : '';
        $result  = $this->query(
            "SELECT k.*, u.nama FROM konsultasi k
             JOIN users u ON k.user_id=u.id
             $where ORDER BY k.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDetail(int $id): ?array
    {
        $stmt = $this->prepare(
            "SELECT k.*, u.nama as user_nama FROM konsultasi k
             JOIN users u ON k.user_id=u.id WHERE k.id=?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function getDetailByUser(int $id, int $userId): ?array
    {
        $stmt = $this->prepare("SELECT * FROM konsultasi WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function getBalasan(int $konsultasiId): array
    {
        $stmt = $this->prepare(
            "SELECT b.*, u.nama, u.role FROM balasan_konsultasi b
             JOIN users u ON b.user_id=u.id
             WHERE b.konsultasi_id=? ORDER BY b.created_at ASC"
        );
        $stmt->bind_param('i', $konsultasiId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create(int $userId, string $judul, string $pesan): int
    {
        $stmt = $this->prepare(
            "INSERT INTO konsultasi (user_id, judul, pesan) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('iss', $userId, $judul, $pesan);
        $stmt->execute();
        return $this->lastInsertId();
    }

    public function addBalasan(int $konsultasiId, int $userId, string $pesan): bool
    {
        $stmt = $this->prepare(
            "INSERT INTO balasan_konsultasi (konsultasi_id, user_id, pesan) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('iis', $konsultasiId, $userId, $pesan);
        return $stmt->execute();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->prepare("UPDATE konsultasi SET status=? WHERE id=?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
}