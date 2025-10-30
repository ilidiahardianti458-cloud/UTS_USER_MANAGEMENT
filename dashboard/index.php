<?php
// dashboard/index.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
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
  <h2>Dashboard Admin Gudang</h2>
  <p>Gunakan menu untuk mengelola produk dan profil.</p>
</div>
</body>
</html>
