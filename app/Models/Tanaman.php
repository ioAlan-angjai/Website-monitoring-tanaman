<?php
// app/Models/Tanaman.php

namespace App\Models;

use App\Core\Model;

class Tanaman extends Model
{
    protected string $table = 'tanaman';

    public function getByUser(int $userId, string $status = 'all'): array
    {
        $where = $status !== 'all' ? "AND status='$status'" : '';
        $result = $this->query(
            "SELECT * FROM tanaman WHERE user_id=$userId $where ORDER BY created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAktifByUser(int $userId, int $limit = 6): array
    {
        $result = $this->query(
            "SELECT * FROM tanaman WHERE user_id=$userId AND status='aktif'
             ORDER BY created_at DESC LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countByStatus(int $userId, string $status): int
    {
        $res = $this->query(
            "SELECT COUNT(*) c FROM tanaman WHERE user_id=$userId AND status='$status'"
        );
        return (int)$res->fetch_assoc()['c'];
    }

    public function hasAny(int $userId): bool
    {
        $res = $this->query("SELECT COUNT(*) c FROM tanaman WHERE user_id=$userId");
        return (bool)$res->fetch_assoc()['c'];
    }

    public function create(array $data): int
    {
        $stmt = $this->prepare(
            "INSERT INTO tanaman (user_id, nama_tanaman, jenis, tanggal_tanam, estimasi_panen,
             lokasi, catatan, suhu, ph_tanah, kelembapan, jenis_pupuk)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'isssissidis',
            $data['user_id'], $data['nama_tanaman'], $data['jenis'],
            $data['tanggal_tanam'], $data['estimasi_panen'], $data['lokasi'],
            $data['catatan'], $data['suhu'], $data['ph_tanah'],
            $data['kelembapan'], $data['jenis_pupuk']
        );
        $stmt->execute();
        return $this->lastInsertId();
    }

    public function updateStatus(int $id, int $userId, string $status): bool
    {
        $allowed = ['aktif', 'panen', 'mati'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->prepare("UPDATE tanaman SET status=? WHERE id=? AND user_id=?");
        $stmt->bind_param('sii', $status, $id, $userId);
        return $stmt->execute();
    }

    public function deleteByUser(int $id, int $userId): bool
    {
        $stmt = $this->prepare("DELETE FROM tanaman WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }

    public function getForDropdown(int $userId): array
    {
        $result = $this->query(
            "SELECT id, nama_tanaman FROM tanaman WHERE user_id=$userId AND status='aktif'"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Tambahkan fungsi ini di dalam file: app/Models/Tanaman.php

// Ganti fungsi getAllTanamanAdmin() di app/Models/Tanaman.php dengan ini:

public function getAllTanamanAdmin(): array
{
    $query = "SELECT t.*, u.nama as nama_user 
              FROM tanaman t 
              LEFT JOIN users u ON t.user_id = u.id 
              ORDER BY t.id DESC";
              
    // Menggunakan query khas mysqli bawaan PHP
    $result = $this->db->query($query);
    
    if ($result) {
        // Mengambil semua data sebagai array asosiatif khas mysqli
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    return [];
}

// Tambahkan fungsi ini di dalam file: app/Models/Tanaman.php

public function find(int $id): ?array
{
    // Mengambil koneksi link mysqli asli secara internal (aman dari pembatasan protected)
    $conn = $this->db->conn ?? $this->db->db ?? $this->db;
    
    $id = (int)$id;
    $query = "SELECT * FROM tanaman WHERE id = {$id}";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}
}