<?php
// app/Models/Jadwal.php

namespace App\Models;

use App\Core\Model;

class Jadwal extends Model
{
    protected string $table = 'jadwal';

    // Dipakai oleh User Biasa untuk melihat jadwal miliknya sendiri
    public function getByUser(int $userId, string $filter = 'semua'): array
    {
        $where = '';
        if ($filter === 'hari_ini') $where = "AND j.tanggal_jadwal=CURDATE()";
        elseif ($filter === 'pending') $where = "AND j.status='pending'";
        elseif ($filter === 'selesai') $where = "AND j.status='selesai'";

        $result = $this->query(
            "SELECT j.*, t.nama_tanaman FROM jadwal j
             JOIN tanaman t ON j.tanaman_id=t.id
             WHERE j.user_id=$userId $where
             ORDER BY j.tanggal_jadwal ASC, j.waktu ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Dipakai oleh Admin untuk menarik seluruh data jadwal dari semua user
    public function getAllJadwalAdmin(string $filter = 'semua'): array
    {
        $where = '';
        if ($filter === 'hari_ini') $where = "WHERE j.tanggal_jadwal=CURDATE()";
        elseif ($filter === 'pending') $where = "WHERE j.status='pending'";
        elseif ($filter === 'selesai') $where = "WHERE j.status='selesai'";

        // Melakukan JOIN tambahan ke tabel users agar Admin tahu siapa pemilik jadwal tersebut
        $queryStr = "SELECT j.*, t.nama_tanaman, u.nama as nama_user 
                     FROM jadwal j
                     JOIN tanaman t ON j.tanaman_id=t.id
                     JOIN users u ON t.user_id=u.id
                     $where
                     ORDER BY j.tanggal_jadwal DESC, j.waktu ASC";

        $result = $this->query($queryStr);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getHariIni(int $userId, int $limit = 5): array
    {
        $result = $this->query(
            "SELECT j.*, t.nama_tanaman FROM jadwal j
             JOIN tanaman t ON j.tanaman_id=t.id
             WHERE j.user_id=$userId AND j.tanggal_jadwal=CURDATE() AND j.status='pending'
             ORDER BY j.waktu ASC LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countHariIni(int $userId): int
    {
        $res = $this->query(
            "SELECT COUNT(*) c FROM jadwal WHERE user_id=$userId
             AND tanggal_jadwal=CURDATE() AND status='pending'"
        );
        return (int)$res->fetch_assoc()['c'];
    }

    public function create(array $data): bool
    {
        $stmt = $this->prepare(
            "INSERT INTO jadwal (tanaman_id, user_id, jenis_perawatan, tanggal_jadwal, waktu, catatan)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'iissss',
            $data['tanaman_id'], $data['user_id'], $data['jenis_perawatan'],
            $data['tanggal_jadwal'], $data['waktu'], $data['catatan']
        );
        return $stmt->execute();
    }

    public function createDefault(int $tanamanId, int $userId): bool
    {
        $stmt = $this->prepare(
            "INSERT INTO jadwal (tanaman_id, user_id, jenis_perawatan, tanggal_jadwal, waktu)
             VALUES (?, ?, 'penyiraman', CURDATE(), '07:00:00')"
        );
        $stmt->bind_param('ii', $tanamanId, $userId);
        return $stmt->execute();
    }

    public function selesai(int $id, int $userId): bool
    {
        $stmt = $this->prepare("UPDATE jadwal SET status='selesai' WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }

    // Digunakan oleh Admin untuk mengubah status selesai jadwal milik user mana pun
    public function selesaiAdmin(int $id): bool
    {
        $stmt = $this->prepare("UPDATE jadwal SET status='selesai' WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function hapus(int $id, int $userId): bool
    {
        $stmt = $this->prepare("DELETE FROM jadwal WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $id, $userId);
        return $stmt->execute();
    }

    // Digunakan oleh Admin untuk menghapus jadwal milik user mana pun
    public function hapusAdmin(int $id): bool
    {
        $stmt = $this->prepare("DELETE FROM jadwal WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function updateTerlewat(int $userId): void
    {
        $role = $_SESSION['role'] ?? 'user';

        if ($role === 'admin') {
            // Jika admin, lakukan pembaruan status 'terlewat' secara massal untuk semua user
            $this->query(
                "UPDATE jadwal SET status='terlewat'
                 WHERE status='pending' AND tanggal_jadwal < CURDATE()"
            );
        } else {
            // Jika user biasa, hanya update milik dirinya sendiri
            $this->query(
                "UPDATE jadwal SET status='terlewat'
                 WHERE user_id=$userId AND status='pending' AND tanggal_jadwal < CURDATE()"
            );
        }
    }

    /**
     * Method internal aman untuk membuat notifikasi dari Admin.
     * Menggunakan scope internal Model sehingga aman dari pembatasan protected method query().
     */
    public function kirimNotifAdmin(int $targetUserId, string $jenisPerawatan, string $namaTanaman, string $tanggalJadwal, string $waktu): void
    {
        $perawatanClean = ucwords(str_replace('_', ' ', $jenisPerawatan));
        $tanggalFormat  = date('d M Y', strtotime($tanggalJadwal));

        $judulNotif = 'Jadwal Perawatan Baru dari Admin';
        $pesanNotif = "Admin telah menambahkan jadwal perawatan [{$perawatanClean}] baru untuk tanaman {$namaTanaman} Anda pada tanggal {$tanggalFormat} jam {$waktu}.";
        
        $judulEscaped = addslashes($judulNotif);
        $pesanEscaped = addslashes($pesanNotif);

        $queryNotif = "INSERT INTO notifikasi (user_id, tipe, judul, pesan, is_read, created_at) 
                       VALUES ('{$targetUserId}', 'jadwal', '{$judulEscaped}', '{$pesanEscaped}', 0, NOW())";
        
        $this->query($queryNotif);
    }
}