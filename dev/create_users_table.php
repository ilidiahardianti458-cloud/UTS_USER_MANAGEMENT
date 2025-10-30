<?php
include 'conn_db.php';

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin_gudang') DEFAULT 'admin_gudang',
    status ENUM('pending','active') DEFAULT 'pending',
    activation_code VARCHAR(128) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' berhasil dibuat.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
