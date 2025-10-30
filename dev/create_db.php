<?php
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$port       = 3307;
$newdb      = "gudangbaru";

// Buat koneksi sementara 
$conn = new mysqli($servername, $username, $password, "", $port);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Buat database baru
$sql = "CREATE DATABASE IF NOT EXISTS `$newdb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database '$newdb' berhasil dibuat.<br>";
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
