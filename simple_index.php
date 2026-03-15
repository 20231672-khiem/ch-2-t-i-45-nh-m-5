<?php
// simple_index.php - Trang cực đơn giản để test
session_start();
require_once 'includes/config.php';

$sql = "SELECT * FROM products LIMIT 8";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PhoneStore - Simple</title>
    <style>
        .product {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            width: 200px;
            display: inline-block;
            vertical-align: top;
        }
        .product-img {
            width: 100%;
            height: 150px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .price {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>PhoneStore - Simple View</h1>
    
    <?php while($row = mysqli_fetch_assoc($result)): 
        $price = $row['sale_price'] ?: $row['price'];
    ?>
    <div class="product">
        <div class="product-img">
            <?php
            // SVG đơn giản
            $color = '3498db';
            if (stripos($row['name'], 'samsung') !== false) $color = '2ecc71';
            if (stripos($row['name'], 'xiaomi') !== false) $color = 'e74c3c';
            
            echo '<svg width="180" height="120">
                    <rect width="100%" height="100%" fill="#' . $color . '"/>
                    <text x="50%" y="40%" font-family="Arial" font-size="12" fill="white" text-anchor="middle">
                        ' . substr($row['name'], 0, 15) . '
                    </text>
                    <text x="50%" y="60%" font-family="Arial" font-size="14" fill="white" text-anchor="middle">
                        ' . number_format($price, 0, ',', '.') . '₫
                    </text>
                  </svg>';
            ?>
        </div>
        <div>
            <strong><?php echo $row['name']; ?></strong><br>
            <span class="price"><?php echo number_format($price, 0, ',', '.'); ?>₫</span>
        </div>
    </div>
    <?php endwhile; ?>
    
    <br><br>
    <a href="fix_images.php">🔄 Tạo hình ảnh tự động</a> | 
    <a href="index.php">🏠 Về trang chủ đầy đủ</a>
</body>
</html>