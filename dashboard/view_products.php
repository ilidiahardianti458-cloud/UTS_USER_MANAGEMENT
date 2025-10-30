<?php
session_start();
require_once __DIR__ . '/../dev/conn_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch semua produk
$res = $conn->query("SELECT p.*, u.fullname as creator 
                     FROM products p 
                     LEFT JOIN users u ON p.created_by = u.id 
                     ORDER BY p.id DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Produk</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
<h2>Daftar Produk</h2>
<p><a href="add_products.php">Tambah Produk</a></p>

<table class="table">
<thead>
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Qty</th>
    <th>Harga</th>
    <th>Creator</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($r = $res->fetch_assoc()): ?>
<tr>
    <td><?php echo $r['id']; ?></td>
    <td><?php echo htmlspecialchars($r['name']); ?></td>
    <td><?php echo $r['quantity']; ?></td>
    <td><?php echo number_format($r['price'], 2); ?></td>
    <td><?php echo htmlspecialchars($r['creator']); ?></td>
    <td>
        <a href="edit_products.php?id=<?php echo $r['id']; ?>">Edit</a> |
        <a href="view_products.php?delete=<?php echo $r['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<?php
// DELETE PRODUK
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: view_products.php");
        exit;
    }
}
?>
</div>
</body>
</html>
