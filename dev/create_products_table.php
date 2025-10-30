<?php
include 'conn_db.php';

$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    quantity INT DEFAULT 0,
    price DECIMAL(12,2) DEFAULT 0.00,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' berhasil dibuat.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
