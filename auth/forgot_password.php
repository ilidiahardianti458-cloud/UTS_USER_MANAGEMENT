<?php
// auth/forgot_password.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Masukkan email valid.";
    } else {
        $stmt = $conn->prepare("SELECT fullname FROM users WHERE email=? AND status='active' LIMIT 1");
        if (!$stmt) { die("Prepare failed: " . $conn->error); }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            // Buat token dari email + timestamp + key rahasia
            $secret = 'supersecretkey';
            $token = urlencode(base64_encode($email . ':' . (time()+3600) . ':' . hash_hmac('sha256', $email, $secret)));
            $link = BASE_URL . "/auth/reset_password.php?token=$token";

            // Kirim email menggunakan PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = MAIL_PORT;

                $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
                $mail->addAddress($email, $user['fullname']);
                $mail->isHTML(true);
                $mail->Subject = "Reset Password - Admin Gudang";
                $mail->Body    = "
                    <h3>Halo {$user['fullname']},</h3>
                    <p>Kamu meminta reset password. Klik tautan berikut untuk membuat password baru:</p>
                    <a href='$link'>$link</a>
                    <p>Link berlaku 1 jam. Jika bukan kamu, abaikan email ini.</p>
                ";
                $mail->send();
                $success = "Link reset password telah dikirim ke email Anda.";
            } catch (Exception $e) {
                $success = "Email gagal dikirim. Gunakan tautan ini: <a href='$link'>$link</a>";
            }
        } else {
            $error = "Email tidak ditemukan atau akun belum aktif.";
        }
        $stmt->close();
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lupa Password - Admin Gudang</title>
<link rel="stylesheet" href="../assets/style.css">
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
.container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
h2 { text-align: center; margin-bottom: 20px; color: #333; }
input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
button { width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
.message { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
.message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
p { text-align: center; margin-top: 15px; }
p a { color: #007bff; text-decoration: none; }
p a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">
<h2>Lupa Password</h2>
<?php if($error) echo "<div class='message error'>{$error}</div>"; ?>
<?php if($success) echo "<div class='message success'>{$success}</div>"; ?>

<form method="post">
  <input type="email" name="email" placeholder="Masukkan email aktif" required>
  <button type="submit">Kirim Link Reset</button>
</form>

<p><a href="login.php">Kembali ke Login</a></p>
</div>
</body>
</html>
