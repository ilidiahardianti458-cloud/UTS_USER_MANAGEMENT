<?php
// auth/reset_password.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$secret = 'supersecretkey';
$error = '';
$success = '';
$email = '';
$token = $_GET['token'] ?? '';

// Validasi token
if ($token) {
    $decoded = base64_decode(urldecode($token));
    $parts = explode(':', $decoded);
    if (count($parts) === 3) {
        list($email, $expire, $hash) = $parts;
        // cek waktu expired
        if (time() > $expire) {
            $error = "Token sudah kadaluarsa.";
            $token = '';
        } elseif (!hash_hmac('sha256', $email, $secret) === $hash) {
            $error = "Token tidak valid.";
            $token = '';
        }
    } else {
        $error = "Token tidak valid.";
        $token = '';
    }
} else {
    $error = "Token tidak ditemukan.";
}

// jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token) {
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if ($pass1 === '' || $pass2 === '') {
        $error = "Isi semua field.";
    } elseif ($pass1 !== $pass2) {
        $error = "Password tidak sama.";
    } else {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=? AND status='active'");
        if (!$stmt) { die("Prepare failed: " . $conn->error); }
        $stmt->bind_param("ss", $hash, $email);
        if ($stmt->execute()) {
            $success = "✅ Password berhasil diubah. <a href='login.php'>Login sekarang</a>.";
            $token = ''; // agar form hilang
        } else {
            $error = "Gagal mengubah password.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Reset Password</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
<h2>Reset Password</h2>
<?php if($error) echo "<div class='message error'>{$error}</div>"; ?>
<?php if($success) echo "<div class='message success'>{$success}</div>"; ?>

<?php if($token): ?>
<form method="post">
  <input type="password" name="password" placeholder="Password baru" required><br>
  <input type="password" name="password2" placeholder="Ulangi password baru" required><br>
  <button type="submit">Reset Password</button>
</form>
<?php endif; ?>
</div>
</body>
</html>
