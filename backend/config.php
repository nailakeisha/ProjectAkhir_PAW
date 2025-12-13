<?php
// config.php
$host = "localhost";
$username = "root";
$password = "";
$database = "niseva_agro"; // Pastikan nama database benar

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>