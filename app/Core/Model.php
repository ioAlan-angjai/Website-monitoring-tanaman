<?php
// app/Core/Model.php — Base class untuk semua Model

namespace App\Core;

abstract class Model
{
    protected \mysqli $db;
    protected string  $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Jalankan query sederhana, return mysqli_result.
     */
    protected function query(string $sql): \mysqli_result|bool
    {
        return $this->db->query($sql);
    }

    /**
     * Buat prepared statement.
     */
    protected function prepare(string $sql): \mysqli_stmt|false
    {
        return $this->db->prepare($sql);
    }

    /**
     * Ambil satu baris dari tabel berdasarkan id.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    /**
     * Ambil semua baris dari tabel.
     */
    public function all(string $order = 'id DESC'): array
    {
        $result = $this->query("SELECT * FROM {$this->table} ORDER BY {$order}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Hapus baris berdasarkan id.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Kembalikan ID insert terakhir.
     */
    protected function lastInsertId(): int
    {
        return (int) $this->db->insert_id;
    }
}