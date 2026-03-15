<?php
// File đơn giản nhất để test
echo "<!DOCTYPE html>
<html>
<head>
    <title>PhoneStore Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; margin: 10px; display: inline-block; }
    </style>
</head>
<body>
    <h1>PhoneStore - Test Page</h1>
    <p>Website đang hoạt động!</p>
    
    <?php
    // Test database connection
    $conn = mysqli_connect('localhost', 'root', '', 'phone_store');
    if ($conn) {
        echo '<p style=\"color:green;\">✅ Database connected</p>';
        
        $result = mysqli_query($conn, 'SELECT * FROM products LIMIT 4');
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<h3>Sản phẩm trong database:</h3>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class=\"product\">';
                echo '<h4>' . $row['name'] . '</h4>';
                echo '<p>Giá: ' . number_format($row['price'], 0, ',', '.') . '₫</p>';
                echo '</div>';
            }
        } else {
            echo '<p style=\"color:orange;\">⚠ No products found</p>';
        }
    } else {
        echo '<p style=\"color:red;\">❌ Database connection failed</p>';
    }
    ?>
    
    <hr>
    <a href=\"index.php\">Quay lại trang chủ chính</a>
</body>
</html>";