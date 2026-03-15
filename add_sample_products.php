<?php
// add_sample_products.php
require_once 'includes/config.php';

$sample_products = [
    ['iPhone 15 Pro Max 256GB', 'iphone-15-pro-max.jpg', 32990000, 1],
    ['Samsung Galaxy S23 Ultra', 'samsung-s23-ultra.jpg', 28990000, 2],
    ['Xiaomi 13 Pro', 'xiomi13pro.jpg', 21990000, 3],
    ['OPPO Find N3 Flip', 'oppon3flip.jpg', 19990000, 4],
    ['Realme GT5', 'realme5gt.jpg', 14990000, 5],
    ['AirPods Pro 2', 'airpodspro2.jpg', 5990000, 6],
    ['Pin sạc dự phòng 20.000mAh', 'pin20000mah.jpg', 890000, 7],
    ['Ốp lưng iPhone 15 Pro Max', 'oplungip15promax.jpg', 350000, 8],
    ['Cáp sạc nhanh 100W', 'cap100w.jpg', 290000, 9]
];

foreach ($sample_products as $product) {
    $name = mysqli_real_escape_string($conn, $product[0]);
    $image = mysqli_real_escape_string($conn, $product[1]);
    $price = $product[2];
    $category_id = $product[3];
    
    $check = mysqli_query($conn, "SELECT id FROM products WHERE name = '$name'");
    if (mysqli_num_rows($check) == 0) {
        $sql = "INSERT INTO products (name, image, price, category_id, created_at) 
                VALUES ('$name', '$image', $price, $category_id, NOW())";
        mysqli_query($conn, $sql);
        echo "✅ Đã thêm: $name<br>";
    } else {
        echo "⚠ Đã tồn tại: $name<br>";
    }
}

echo "<br><a href='pages/products.php'>Xem sản phẩm</a>";
?>