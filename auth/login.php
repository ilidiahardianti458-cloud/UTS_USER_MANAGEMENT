<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';

$error = '';
$success = '';

// Cek notifikasi dari redirect (misal setelah ubah password)
if (isset($_GET['msg']) && $_GET['msg'] === 'pass_changed') {
    $success = "Password berhasil diubah. Silakan login.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $error = "Masukkan email & password.";
    } else {
        $stmt = $conn->prepare("SELECT id,fullname,password,role,status FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($user = $res->fetch_assoc()) {
            if (!password_verify($pass, $user['password'])) {
                $error = "Email atau password salah.";
            } elseif ($user['status'] !== 'active') {
                $error = "Akun belum aktif. Periksa email untuk aktivasi.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../dashboard/index.php");
                exit;
            }
        } else {
            $error = "Email atau password salah.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
<h2>Login Admin Gudang</h2>

<?php 
if ($success) echo "<div class='message success'>{$success}</div>";
if ($error) echo "<div class='message error'>{$error}</div>";
?>

<form method="post">
  <input type="email" name="email" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>

<p><a href="register.php">Daftar</a> | <a href="forgot_password.php">Lupa Password?</a></p>
</div>
</body>
</html>
