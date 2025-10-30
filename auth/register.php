<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../dev/conn_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $activation_code = md5(uniqid(rand(), true));

    // Cek email sudah terdaftar
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "<script>alert('Email ini sudah digunakan mohon masukkan email lain!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, activation_code) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $fullname, $email, $password, $activation_code);
        if ($stmt->execute()) {
            $activation_link = BASE_URL . "/auth/activate.php?code=$activation_code";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // atau 'tls'
                $mail->Port       = MAIL_PORT;

                $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
                $mail->addAddress($email, $fullname);
                $mail->isHTML(true);
                $mail->Subject = "Aktivasi Akun Admin Gudang";
                $mail->Body    = "
                    <h3>Halo $fullname,</h3>
                    <p>Terima kasih sudah mendaftar. Klik tautan berikut untuk aktivasi akun Anda:</p>
                    <a href='$activation_link'>$activation_link</a>
                ";

                $mail->send();
                echo "<p>Registrasi berhasil! Cek email kamu untuk aktivasi.</p>";
            } catch (Exception $e) {
                echo "<p>Email gagal dikirim. Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo "Terjadi kesalahan pada server.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
<h2>Registrasi Admin Gudang</h2>
<form method="POST">
  <input type="text" name="fullname" placeholder="Nama Lengkap" required><br>
  <input type="email" name="email" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Daftar</button>
</form>
