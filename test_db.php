<?php
// test_db.php
require_once 'includes/config.php';

echo "<h3>Kiểm tra Database</h3>";

// Kiểm tra kết nối
if ($conn) {
    echo "✅ Kết nối database thành công<br>";
    
    // Kiểm tra bảng products
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'products'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Bảng 'products' tồn tại<br>";
        
        // Đếm số sản phẩm
        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
        $row = mysqli_fetch_assoc($count);
        echo "📊 Tổng số sản phẩm: " . $row['total'] . "<br>";
        
        // Hiển thị 5 sản phẩm đầu tiên
        $products = mysqli_query($conn, "SELECT id, name, image, price FROM products LIMIT 5");
        echo "<h4>5 sản phẩm đầu tiên:</h4>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Tên</th><th>Ảnh</th><th>Giá</th></tr>";
        while ($p = mysqli_fetch_assoc($products)) {
            echo "<tr>";
            echo "<td>{$p['id']}</td>";
            echo "<td>{$p['name']}</td>";
            echo "<td>{$p['image']}</td>";
            echo "<td>{$p['price']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Bảng 'products' KHÔNG tồn tại<br>";
    }
} else {
    echo "❌ Lỗi kết nối database";
}
?>