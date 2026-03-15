<?php
// check_images.php
require_once 'includes/config.php';
echo "<h3>🔍 Kiểm tra hình ảnh</h3>";

// Kiểm tra thư mục
$image_dir = 'assets/images/products/';
echo "Thư mục hình ảnh: $image_dir<br>";

if (!is_dir($image_dir)) {
    echo "❌ Thư mục không tồn tại!<br>";
    mkdir($image_dir, 0777, true);
    echo "✅ Đã tạo thư mục<br>";
} else {
    echo "✅ Thư mục tồn tại<br>";
}

// Liệt kê file
echo "<h4>📁 File trong thư mục:</h4>";
$files = scandir($image_dir);
if ($files) {
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filepath = $image_dir . $file;
            $size = filesize($filepath);
            echo "<li>$file (" . round($size/1024) . "KB) - " . 
                 (($size > 1000) ? "✅" : "⚠") . "</li>";
        }
    }
    echo "</ul>";
}

// Kiểm tra database
$conn = mysqli_connect('localhost', 'root', '', 'phonestore_db');
if ($conn) {
    echo "<h4>🗄️ Kiểm tra database:</h4>";
    
    // THÊM DỮ LIỆU MẪU NẾU CẦN
    $sample_products = [
        ['name' => 'Samsung Galaxy Z Fold5 12GB/512GB', 'image' => 'samsungzfold5.jpg'],
        ['name' => 'Samsung Galaxy A54 5G 8GB/128GB', 'image' => 'samsunga54.jpg'],
        ['name' => 'Xiaomi 13 Pro 12GB/256GB', 'image' => 'xiomi13pro.jpg'],
        ['name' => 'Xiaomi Redmi Note 12 Pro 8GB/128GB', 'image' => 'redminote12pro.jpg'],
        ['name' => 'OPPO Find N3 Flip 12GB/256GB', 'image' => 'oppon3flip.jpg'],
        ['name' => 'OPPO Reno10 Pro 5G 12GB/256GB', 'image' => 'opporeno10pro.jpg'],
        ['name' => 'Realme GT5 240W 16GB/1TB', 'image' => 'realme5gt.jpg'],
        ['name' => 'Samsung Galaxy Buds2 Pro', 'image' => 'samsungbuds2pro.jpg'],
        ['name' => 'AirPods Pro 2', 'image' => 'airpodspro2.jpg'],
        ['name' => 'Pin sạc dự phòng 20.000mAh', 'image' => 'pin20000mah.jpg'],
        ['name' => 'Ốp lưng iPhone 15 Pro Max', 'image' => 'oplungip15promax.jpg'],
        ['name' => 'Cáp sạc nhanh 100W', 'image' => 'cap100w.jpg']
    ];
    
    // Kiểm tra và thêm sản phẩm mẫu nếu chưa có
    foreach ($sample_products as $product) {
        $check = mysqli_query($conn, "SELECT id FROM products WHERE image = '{$product['image']}'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($conn, "INSERT INTO products (name, image, price, category_id) 
                                VALUES ('{$product['name']}', '{$product['image']}', 0, 1)");
        }
    }
    
    // Lấy tất cả sản phẩm để kiểm tra
   $result = mysqli_query($conn, "SELECT id, name, image FROM products");
while ($row = mysqli_fetch_assoc($result)) {
    $file = 'assets/images/products/' . $row['image'];
    if (file_exists($file)) {
        echo "✅ {$row['name']} - {$row['image']}<br>";
    } else {
        echo "❌ {$row['name']} - {$row['image']} (FILE KHÔNG TỒN TẠI)<br>";
    }
}
    $missing_count = 0;
    $total_count = 0;
    
    while ($row = mysqli_fetch_assoc($result)) {
        $total_count++;
        $image_file = $image_dir . $row['image'];
        $exists = file_exists($image_file);
        
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['image']}</td>";
        echo "<td>$image_file</td>";
        
        if ($exists) {
            echo "<td style='color: green; text-align: center;'>✅</td>";
        } else {
            echo "<td style='color: red; text-align: center;'>❌</td>";
            $missing_count++;
        }
        
        echo "</tr>";
    }
    echo "</table>";
    
    // Thống kê
    echo "<h4>📊 Thống kê:</h4>";
    echo "Tổng số sản phẩm: $total_count<br>";
    echo "Số ảnh thiếu: $missing_count<br>";
    echo "Tỷ lệ hoàn thành: " . round(($total_count - $missing_count) / $total_count * 100, 1) . "%";
    
    mysqli_close($conn);
} else {
    echo "<div style='color: red; padding: 10px; background: #ffe6e6; border-radius: 5px;'>";
    echo "❌ Lỗi kết nối database: " . mysqli_connect_error();
    echo "</div>";
}

echo "<hr>";
echo "<div style='margin-top: 20px;'>";
echo "<a href='fix_images.php' style='background:#0d6efd; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; margin-right: 10px;'>🔄 Tự động fix hình ảnh</a>";
echo "<a href='upload_images.php' style='background:#198754; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>📤 Upload ảnh thiếu</a>";
echo "</div>";
?>