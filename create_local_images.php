<?php
// create_local_images.php - Tạo hình ảnh local bằng GD Library
echo "<h3>🎨 Tạo hình ảnh mẫu local</h3>";

// Danh sách sản phẩm
$products = [
    [
        'name' => 'iPhone 15 Pro Max',
        'color' => [52, 152, 219], // Xanh Apple
        'price' => '29.990.000₫'
    ],
    [
        'name' => 'Samsung S23 Ultra',
        'color' => [46, 204, 113], // Xanh Samsung
        'price' => '25.990.000₫'
    ],
    [
        'name' => 'Xiaomi 13 Pro',
        'color' => [231, 76, 60], // Đỏ Xiaomi
        'price' => '17.990.000₫'
    ],
    [
        'name' => 'AirPods Pro 2',
        'color' => [26, 188, 156], // Xanh ngọc
        'price' => '5.490.000₫'
    ],
    [
        'name' => 'Samsung Z Fold5',
        'color' => [155, 89, 182], // Tím
        'price' => '36.990.000₫'
    ],
    [
        'name' => 'iPhone 14 Pro Max',
        'color' => [149, 165, 166], // Xám
        'price' => '23.990.000₫'
    ],
    [
        'name' => 'Xiaomi Redmi Note 12',
        'color' => [230, 126, 34], // Cam
        'price' => '6.990.000₫'
    ],
    [
        'name' => 'Pin sạc dự phòng',
        'color' => [241, 196, 15], // Vàng
        'price' => '890.000₫'
    ]
];

// Tạo thư mục
if (!is_dir('assets/images/products')) {
    mkdir('assets/images/products', 0777, true);
}

// Tạo hình cho mỗi sản phẩm
foreach ($products as $index => $product) {
    $filename = strtolower(str_replace(' ', '-', $product['name'])) . '.jpg';
    $filepath = 'assets/images/products/' . $filename;
    
    // Tạo hình 400x300
    $image = imagecreatetruecolor(400, 300);
    
    // Màu nền
    $bg_color = imagecolorallocate($image, $product['color'][0], $product['color'][1], $product['color'][2]);
    imagefill($image, 0, 0, $bg_color);
    
    // Màu chữ trắng
    $text_color = imagecolorallocate($image, 255, 255, 255);
    
    // Vẽ biểu tượng điện thoại
    $phone_color = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 150, 80, 250, 200, $phone_color); // Thân máy
    
    // Màn hình
    $screen_color = imagecolorallocate($image, 200, 200, 200);
    imagefilledrectangle($image, 160, 90, 240, 190, $screen_color);
    
    // Nút home
    $button_color = imagecolorallocate($image, 100, 100, 100);
    imagefilledellipse($image, 200, 240, 30, 30, $button_color);
    
    // Thêm tên sản phẩm
    imagestring($image, 5, 100, 20, $product['name'], $text_color);
    imagestring($image, 4, 130, 260, $product['price'], $text_color);
    
    // Lưu hình
    imagejpeg($image, $filepath, 85);
    imagedestroy($image);
    
    echo "✅ Đã tạo: $filename<br>";
    
    // Cập nhật tên file trong database
    $conn = mysqli_connect('localhost', 'root', '', 'phone_store');
    if ($conn) {
        $clean_name = str_replace(['-', '.jpg'], ' ', $product['name']);
        mysqli_query($conn, "UPDATE products SET image = '$filename' WHERE name LIKE '%$clean_name%' LIMIT 1");
    }
}

echo "<hr><h4>🎉 Đã tạo " . count($products) . " hình ảnh!</h4>";
echo "<a href='index.php'>Xem sản phẩm ngay</a>";

// Cập nhật CSS để hình đẹp hơn
$css = "
.product-img-container {
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.product-img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.product-card:hover .product-img {
    transform: scale(1.1);
}

.price {
    color: #dc3545;
    font-weight: 700;
    font-size: 1.2rem;
}

.old-price {
    color: #6c757d;
    text-decoration: line-through;
    font-size: 0.9rem;
}
";

file_put_contents('assets/css/custom.css', $css);
echo "<br>✅ Đã tạo CSS custom";
?>