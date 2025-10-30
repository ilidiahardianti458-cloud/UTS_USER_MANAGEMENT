<?php
// dashboard/profile.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
$id = $_SESSION['user_id'];
$error = $success = '';

// update fullname
if (isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    if ($fullname === '') $error = "Nama tidak boleh kosong.";
    else {
        $stmt = $conn->prepare("UPDATE users SET fullname=? WHERE id=?");
        $stmt->bind_param("si", $fullname, $id);
        $stmt->execute();
        $_SESSION['fullname'] = $fullname;
        $success = "Profil diperbarui.";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Profil</title>
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
<h2>Profil Saya</h2>
<?php if($error) echo "<div class='message error'>{$error}</div>"; ?>
<?php if($success) echo "<div class='message success'>{$success}</div>"; ?>

<form method="post">
  <label>Nama Lengkap</label>
  <input type="text" name="fullname" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>" required><br>
  <button type="submit" name="update_profile">Simpan</button>
</form>

<hr>
<p>Untuk mengubah password, gunakan menu <a href="change_password.php">Ubah Password</a>.</p>

</div>
</body></html>
