<?php
// debug_products.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra syntax của products.php
$file_content = file_get_contents('products.php');
echo "<h3>Kiểm tra syntax products.php</h3>";

// Kiểm tra các thẻ PHP không đóng
if (substr_count($file_content, '<?php') != substr_count($file_content, '?>')) {
    echo "<div style='color: red;'>⚠ Lỗi: Số lượng thẻ mở và đóng PHP không khớp!</div>";
}

// Tìm các đoạn code PHP bị lỗi
$lines = explode("\n", $file_content);
echo "<h4>Các dòng có thể lỗi:</h4>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
foreach ($lines as $num => $line) {
    $line_num = $num + 1;
    // Tìm các dòng có code PHP nhưng không trong thẻ PHP
    if (preg_match('/if\s*\(.*\)\s*\{/', $line) && !preg_match('/<\?php/', $line)) {
        echo "<strong style='color: red;'>Dòng $line_num:</strong> $line\n";
    }
}
echo "</pre>";

// Hiển thị phần pagination
echo "<h4>Phần Pagination trong file:</h4>";
echo "<pre style='background: #e8f4f8; padding: 10px;'>";
$in_pagination = false;
foreach ($lines as $num => $line) {
    $line_num = $num + 1;
    if (strpos($line, 'Pagination') !== false || strpos($line, 'pagination') !== false) {
        $in_pagination = true;
    }
    if ($in_pagination) {
        echo "Dòng $line_num: " . htmlspecialchars($line) . "\n";
        if (strpos($line, '</nav>') !== false) {
            $in_pagination = false;
        }
    }
}
echo "</pre>";
?>