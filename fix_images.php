<?php
// fix_images.php - Tự động fix hình ảnh
echo "<h2>🔄 Tự động fix hình ảnh PhoneStore</h2>";

// 1. Tạo thư mục nếu chưa có
$image_dir = 'assets/images/products/';
if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
    echo "✅ Đã tạo thư mục: $image_dir<br>";
}

// 2. Kết nối database
$conn = mysqli_connect('localhost', 'root', '', 'phone_store');
if (!$conn) {
    die("❌ Không thể kết nối database");
}

// 3. Lấy danh sách sản phẩm
$result = mysqli_query($conn, "SELECT * FROM products");
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo "<p>📊 Tìm thấy " . count($products) . " sản phẩm</p>";

// 4. Tạo hình ảnh cho từng sản phẩm
foreach ($products as $product) {
    $filename = $product['image'];
    
    // Nếu không có tên file, tạo từ tên sản phẩm
    if (empty($filename) || $filename == 'default.jpg') {
        $filename = generateImageName($product['name']);
    }
    
    $filepath = $image_dir . $filename;
    
    // Nếu file chưa tồn tại, tạo mới
    if (!file_exists($filepath)) {
        // Tạo hình ảnh SVG đơn giản
        $svg = createProductSVG($product);
        
        if (file_put_contents($filepath, $svg)) {
            echo "✅ Đã tạo: $filename<br>";
            
            // Cập nhật database
            mysqli_query($conn, "UPDATE products SET image = '$filename' WHERE id = {$product['id']}");
        }
    } else {
        echo "✓ Đã có: $filename<br>";
    }
}

// 5. Tạo hình mẫu cho các sản phẩm phổ biến
$sample_images = [
    'iphone-15-pro-max.jpg' => createiPhoneImage('iPhone 15 Pro Max', '29.990.000₫'),
    'iphone-15-pro.jpg' => createiPhoneImage('iPhone 15 Pro', '26.990.000₫'),
    'samsung-s23-ultra.jpg' => createSamsungImage('Samsung S23 Ultra', '25.990.000₫'),
    'xiaomi-13-pro.jpg' => createXiaomiImage('Xiaomi 13 Pro', '17.990.000₫'),
    'airpods-pro-2.jpg' => createAccessoryImage('AirPods Pro 2', '5.490.000₫')
];

foreach ($sample_images as $filename => $svg) {
    $filepath = $image_dir . $filename;
    if (!file_exists($filepath)) {
        file_put_contents($filepath, $svg);
        echo "🎨 Đã tạo mẫu: $filename<br>";
    }
}

echo "<hr>";
echo "<h3>✅ ĐÃ HOÀN THÀNH!</h3>";
echo "<a href='index.php' style='display:inline-block; padding:15px 30px; background:#28a745; color:white; text-decoration:none; border-radius:8px; font-size:18px; margin:10px;'>🏠 VỀ TRANG CHỦ</a>";
echo "<a href='view_images.php' style='display:inline-block; padding:15px 30px; background:#0d6efd; color:white; text-decoration:none; border-radius:8px; font-size:18px; margin:10px;'>👀 XEM HÌNH ẢNH</a>";

// Hàm tạo tên file
function generateImageName($productName) {
    $name = strtolower($productName);
    $name = preg_replace('/[^a-z0-9\s]/', '', $name);
    $name = preg_replace('/\s+/', '-', $name);
    $name = substr($name, 0, 50);
    return $name . '.svg';
}

// Hàm tạo SVG chung
function createProductSVG($product) {
    $name = htmlspecialchars(substr($product['name'], 0, 30));
    $price = number_format($product['price'], 0, ',', '.') . '₫';
    
    // Xác định màu sắc theo brand
    $color = '#3498db'; // Mặc định xanh
    $name_lower = strtolower($product['name']);
    
    if (strpos($name_lower, 'samsung') !== false) $color = '#2ecc71';
    elseif (strpos($name_lower, 'xiaomi') !== false) $color = '#e74c3c';
    elseif (strpos($name_lower, 'oppo') !== false) $color = '#9b59b6';
    elseif (strpos($name_lower, 'realme') !== false) $color = '#f39c12';
    elseif (strpos($name_lower, 'airpods') !== false) $color = '#1abc9c';
    elseif (strpos($name_lower, 'pin') !== false) $color = '#e67e22';
    elseif (strpos($name_lower, 'cáp') !== false) $color = '#34495e';
    
    return '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:' . $color . ';stop-opacity:1" />
                <stop offset="100%" style="stop-color:#2980b9;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#grad1)" />
        
        <rect x="50" y="50" width="300" height="200" rx="20" fill="rgba(255,255,255,0.1)" stroke="white" stroke-width="2"/>
        
        <text x="200" y="120" font-family="Arial, sans-serif" font-size="20" font-weight="bold" 
              fill="white" text-anchor="middle">
            ' . $name . '
        </text>
        
        <text x="200" y="170" font-family="Arial, sans-serif" font-size="24" font-weight="bold" 
              fill="white" text-anchor="middle">
            ' . $price . '
        </text>
        
        <text x="200" y="250" font-family="Arial, sans-serif" font-size="16" 
              fill="rgba(255,255,255,0.8)" text-anchor="middle">
            PhoneStore.vn
        </text>
    </svg>';
}

// Hàm tạo hình iPhone
function createiPhoneImage($name, $price) {
    return '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3498db;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#2980b9;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#grad1)" />
        
        <!-- iPhone body -->
        <rect x="150" y="80" width="100" height="160" rx="15" fill="rgba(255,255,255,0.9)" stroke="white" stroke-width="2"/>
        <rect x="155" y="85" width="90" height="150" rx="10" fill="#111"/>
        
        <!-- Screen content -->
        <rect x="165" y="95" width="70" height="60" rx="5" fill="#007AFF"/>
        <text x="200" y="130" font-family="Arial" font-size="14" fill="white" text-anchor="middle">iPhone</text>
        
        <!-- Home button -->
        <circle cx="200" cy="240" r="8" fill="rgba(255,255,255,0.5)"/>
        
        <!-- Product info -->
        <text x="200" y="50" font-family="Arial" font-size="20" font-weight="bold" fill="white" text-anchor="middle">
            ' . htmlspecialchars($name) . '
        </text>
        
        <text x="200" y="280" font-family="Arial" font-size="22" font-weight="bold" fill="white" text-anchor="middle">
            ' . $price . '
        </text>
    </svg>';
}

// Hàm tạo hình Samsung
function createSamsungImage($name, $price) {
    return '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#2ecc71;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#27ae60;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#grad1)" />
        
        <!-- Samsung body -->
        <rect x="150" y="80" width="100" height="160" rx="5" fill="rgba(255,255,255,0.9)" stroke="white" stroke-width="2"/>
        <rect x="155" y="85" width="90" height="150" rx="3" fill="#000"/>
        
        <!-- Samsung logo -->
        <ellipse cx="200" cy="130" rx="25" ry="15" fill="#1428A0"/>
        <ellipse cx="200" cy="130" rx="20" ry="10" fill="white"/>
        
        <!-- Product info -->
        <text x="200" y="50" font-family="Arial" font-size="20" font-weight="bold" fill="white" text-anchor="middle">
            ' . htmlspecialchars($name) . '
        </text>
        
        <text x="200" y="280" font-family="Arial" font-size="22" font-weight="bold" fill="white" text-anchor="middle">
            ' . $price . '
        </text>
    </svg>';
}

// Hàm tạo hình Xiaomi
function createXiaomiImage($name, $price) {
    return '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#e74c3c;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#c0392b;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#grad1)" />
        
        <!-- Xiaomi logo -->
        <path d="M180,120 L220,120 L220,160 L180,160 Z" fill="white"/>
        <path d="M185,125 L215,125 L215,155 L185,155 Z" fill="#FF6900"/>
        
        <!-- Product info -->
        <text x="200" y="80" font-family="Arial" font-size="20" font-weight="bold" fill="white" text-anchor="middle">
            ' . htmlspecialchars($name) . '
        </text>
        
        <text x="200" y="200" font-family="Arial" font-size="28" font-weight="bold" fill="white" text-anchor="middle">
            ' . $price . '
        </text>
        
        <text x="200" y="240" font-family="Arial" font-size="16" fill="rgba(255,255,255,0.8)" text-anchor="middle">
            Xiaomi Official
        </text>
    </svg>';
}

// Hàm tạo hình phụ kiện
function createAccessoryImage($name, $price) {
    return '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#1abc9c;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#16a085;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#grad1)" />
        
        <!-- AirPods case -->
        <rect x="150" y="100" width="100" height="60" rx="10" fill="white" stroke="#333" stroke-width="2"/>
        
        <!-- AirPods -->
        <ellipse cx="170" cy="120" rx="15" ry="8" fill="white" stroke="#333" stroke-width="1"/>
        <ellipse cx="230" cy="120" rx="15" ry="8" fill="white" stroke="#333" stroke-width="1"/>
        
        <!-- Product info -->
        <text x="200" y="60" font-family="Arial" font-size="20" font-weight="bold" fill="white" text-anchor="middle">
            ' . htmlspecialchars($name) . '
        </text>
        
        <text x="200" y="200" font-family="Arial" font-size="22" font-weight="bold" fill="white" text-anchor="middle">
            ' . $price . '
        </text>
        
        <text x="200" y="240" font-family="Arial" font-size="14" fill="rgba(255,255,255,0.8)" text-anchor="middle">
            Apple AirPods
        </text>
    </svg>';
}
?>