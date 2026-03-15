<?php
// setup_database.php
require_once 'includes/config.php';

echo "<h3>🔧 Thiết lập database</h3>";

// Tạo bảng categories
$sql1 = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100),
    description TEXT,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Tạo bảng products
$sql2 = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0,
    old_price DECIMAL(10,2),
    image VARCHAR(255),
    category_id INT,
    stock INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
)";

if (mysqli_query($conn, $sql1)) {
    echo "✅ Đã tạo bảng categories<br>";
} else {
    echo "❌ Lỗi tạo bảng categories: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $sql2)) {
    echo "✅ Đã tạo bảng products<br>";
} else {
    echo "❌ Lỗi tạo bảng products: " . mysqli_error($conn) . "<br>";
}

echo "<br><a href='pages/products.php' class='btn btn-primary'>Xem sản phẩm</a>";
?>