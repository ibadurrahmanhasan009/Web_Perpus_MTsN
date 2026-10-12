<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "eperpus_a";

try {
    $koneksi = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass
    );
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal. Periksa pengaturan server dan pastikan database tersedia.");
}
