<?php
include('../dev/conn_db.php');

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Cek token aktivasi
    $stmt = $conn->prepare("SELECT * FROM users WHERE activation_code = ?");
    if (!$stmt) { die("Prepare failed: " . $conn->error); }

    $stmt->bind_param("s", $code);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();

        // Update status menjadi active
        $update = $conn->prepare("UPDATE users SET status='active', activation_code=NULL WHERE id=?");
        if (!$update) { die("Prepare failed: " . $conn->error); }

        $update->bind_param("i", $user['id']);
        if ($update->execute()) {
            // Redirect otomatis ke login setelah sukses
            echo "<script>
                    alert('Akun berhasil diaktifkan! Silakan login.');
                    window.location.href='login.php';
                  </script>";
        } else {
            echo "❌ Gagal mengupdate status user.";
        }
    } else {
        echo "❌ Token tidak valid atau sudah digunakan.";
    }

    $stmt->close();
} else {
    echo "❌ Token aktivasi tidak ditemukan.";
}

$conn->close();
?>
