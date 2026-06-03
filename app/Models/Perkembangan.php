<?php
// app/Models/Perkembangan.php

namespace App\Models;

use App\Core\Model;

class Perkembangan extends Model
{
    protected string $table = 'perkembangan';

    public function getByUser(int $userId): array
    {
        $result = $this->query(
            "SELECT p.*, t.nama_tanaman FROM perkembangan p
             JOIN tanaman t ON p.tanaman_id=t.id
             WHERE t.user_id=$userId ORDER BY p.tanggal DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create(array $data): bool
    {
        $stmt = $this->prepare(
            "INSERT INTO perkembangan (tanaman_id, tanggal, tinggi_cm, kondisi, catatan)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'isdss',
            $data['tanaman_id'], $data['tanggal'],
            $data['tinggi_cm'], $data['kondisi'], $data['catatan']
        );
        return $stmt->execute();
    }
}