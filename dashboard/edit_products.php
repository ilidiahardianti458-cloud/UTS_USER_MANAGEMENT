<?php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$error = $success = '';

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Produk tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['description']);
    $qty  = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    $upd = $conn->prepare("UPDATE products SET name=?, description=?, quantity=?, price=? WHERE id=?");
    $upd->bind_param("ssidi", $name, $desc, $qty, $price, $id);
    $upd->execute();
    $upd->close();

    $success = "Produk berhasil diupdate.";
    header("Location: view_products.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Produk</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
<h2>Edit Produk</h2>
<?php if($success) echo "<div class='message success'>{$success}</div>"; ?>
<form method="post">
  <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
  <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
  <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required><br>
  <input type="number" step="Rp." name="price" value="<?php echo $product['price']; ?>" required><br>
  <button type="submit" name="update">Simpan</button>
</form>
<p><a href="view_products.php">Kembali ke Daftar Produk</a></p>
</div>
</body>
</html>
