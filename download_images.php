<?php
// download_images.php - Tải hình ảnh mẫu từ internet
echo "<h3>📥 Tải hình ảnh mẫu cho PhoneStore</h3>";

// Danh sách hình ảnh cần tải với URL thực tế
$images = [
    'iphone-15-pro-max.jpg' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=400&h=300&fit=crop',
    'iphone-15-pro.jpg' => 'https://images.unsplash.com/photo-1695048133056-b32d44cfc320?w=400&h=300&fit=crop',
    'samsung-s23-ultra.jpg' => 'https://images.unsplash.com/photo-1676823091895-18b89c4c5f87?w=400&h=300&fit=crop',
    'xiaomi-13-pro.jpg' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w-400&h=300&fit=crop',
    'airpods-pro-2.jpg' => 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=400&h=300&fit=crop',
    'samsung-z-fold5.jpg' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=300&fit=crop',
    'default.jpg' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=300&fit=crop'
];

// Tạo thư mục nếu chưa có
if (!is_dir('assets/images/products')) {
    mkdir('assets/images/products', 0777, true);
}

// Tải từng hình
foreach ($images as $filename => $url) {
    $filepath = 'assets/images/products/' . $filename;
    
    if (!file_exists($filepath)) {
        $image_data = @file_get_contents($url);
        if ($image_data !== false) {
            file_put_contents($filepath, $image_data);
            echo "✅ Đã tải: $filename<br>";
        } else {
            echo "⚠ Không thể tải: $filename<br>";
            
            // Tạo hình placeholder đơn giản
            $placeholder = imagecreatetruecolor(400, 300);
            $bg_color = imagecolorallocate($placeholder, 52, 152, 219);
            $text_color = imagecolorallocate($placeholder, 255, 255, 255);
            imagefill($placeholder, 0, 0, $bg_color);
            
            // Tên sản phẩm (không đuôi .jpg)
            $product_name = str_replace(['-', '.jpg'], [' ', ''], $filename);
            imagestring($placeholder, 5, 50, 140, $product_name, $text_color);
            imagestring($placeholder, 3, 100, 160, 'PhoneStore', $text_color);
            
            imagejpeg($placeholder, $filepath, 80);
            echo "✅ Đã tạo placeholder: $filename<br>";
        }
    } else {
        echo "✓ Đã có: $filename<br>";
    }
}

echo "<hr><h4>✅ Hoàn thành!</h4>";
echo "<a href='index.php'>Xem sản phẩm với hình ảnh</a>";
?>