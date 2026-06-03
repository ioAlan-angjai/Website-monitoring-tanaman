<?php
// app/Core/Database.php — Singleton koneksi database

namespace App\Core;

class Database
{
    private static ?\mysqli $instance = null;

    public static function getInstance(): \mysqli
    {
        if (self::$instance === null) {
            self::$instance = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$instance->set_charset('utf8mb4');

            if (self::$instance->connect_error) {
                die('Koneksi database gagal: ' . self::$instance->connect_error);
            }
        }
        return self::$instance;
    }

    // Mencegah clone & unserialize
    private function __clone() {}
}