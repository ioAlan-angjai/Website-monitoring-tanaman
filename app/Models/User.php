<?php
// app/Models/User.php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function create(string $nama, string $email, string $hashedPassword): int
    {
        $stmt = $this->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $nama, $email, $hashedPassword);
        $stmt->execute();
        return $this->lastInsertId();
    }

    public function update(int $id, string $nama, string $email, ?string $foto = null): bool
    {
        $stmt = $this->prepare("UPDATE users SET nama=?, email=?, foto=? WHERE id=?");
        $stmt->bind_param('sssi', $nama, $email, $foto, $id);
        return $stmt->execute();
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param('si', $hashedPassword, $id);
        return $stmt->execute();
    }

    public function getAll(): array
    {
        return $this->all('created_at DESC');
    }
}