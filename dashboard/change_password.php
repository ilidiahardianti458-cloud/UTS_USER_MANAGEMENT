<?php
// dashboard/change_password.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
$id = $_SESSION['user_id'];
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_pass'] ?? '';
    $new = $_POST['new_pass'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $u = $res->fetch_assoc();
    if (!password_verify($old, $u['password'])) $error = "Password lama salah.";
    elseif (strlen($new) < 6) $error = "Password minimal 6 karakter.";
    else {
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $upd = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $upd->bind_param("si", $hash, $id);
    if($upd->execute()){
        // Setelah berhasil, redirect ke halaman login
        header("Location: ../auth/login.php?msg=pass_changed");
        exit;
    } else {
        $error = "Gagal mengubah password.";
    }
}

}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Ubah Password</title>
<link rel="stylesheet" href="../assets/style.css">
</head><body>
<div class="navbar">
  <div>Admin Gudang</div>
  <div class="nav-buttons">
    Halo, <?php echo htmlspecialchars($_SESSION['fullname']); ?> |
    <a href="add_products.php" class="nav-btn">Produk</a>
    <a href="profile.php" class="nav-btn">Profil</a>
    <a href="../auth/logout.php" class="nav-btn">Logout</a>
  </div>
</div>


<div class="container">
<h2>Ubah Password</h2>
<?php if($error) echo "<div class='message error'>{$error}</div>"; ?>
<?php if($success) echo "<div class='message success'>{$success}</div>"; ?>
<form method="post">
  <input type="password" name="old_pass" placeholder="Password lama" required><br>
  <input type="password" name="new_pass" placeholder="Password baru" required><br>
  <button type="submit">Ubah Password</button>
</form>
</div>
</body></html>
