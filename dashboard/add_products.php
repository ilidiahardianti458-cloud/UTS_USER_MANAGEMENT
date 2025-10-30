<?php
// dashboard/add_products.php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['add'])) {
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['description']);
    $qty  = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (name, description, quantity, price, created_by) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("ssidi", $name, $desc, $qty, $price, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    header("Location: view_products.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Tambah Produk</title>
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
<h2>Tambah Produk</h2>
<form method="post">
  <input type="text" name="product_name" placeholder="Nama produk" required><br>
  <textarea name="description" placeholder="Deskripsi (opsional)"></textarea><br>
  <input type="number" name="quantity" placeholder="Jumlah" value="0" required><br>
  <input type="number" step="RP." name="price" placeholder="Harga" value="0.00" required><br>
  <button type="submit" name="add">Tambah</button>
</form>
<p><a href="view_products.php">Lihat Produk</a></p>
</div>
</body>
</html>
