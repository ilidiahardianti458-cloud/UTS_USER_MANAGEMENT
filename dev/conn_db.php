<?php
$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "gudangbaru";
$DB_PORT = 3307;

// BASE URL (ubah sesuai nama folder kamu)
define('BASE_URL', 'http://localhost/user-management');

// Konfigurasi email PHPMailer (gunakan App Password Gmail)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'ilid3294@gmail.com');   // Gmail kamu
define('MAIL_PASSWORD', 'ygko ukkj idcn pjoc');       // 16-digit App Password
define('MAIL_PORT', 587);                         // pakai TLS
define('MAIL_FROM', 'ilid3294@gmail.com');
define('MAIL_FROM_NAME', 'Admin Gudang');


// Koneksi Database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}
?>
