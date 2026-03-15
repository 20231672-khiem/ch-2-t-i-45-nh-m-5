<?php
// check_syntax.php
echo "<h3>🔍 Kiểm tra lỗi syntax PHP</h3>";

$files = ['index.php', 'includes/config.php', 'pages/login.php'];
foreach ($files as $file) {
    echo "<h4>Checking: $file</h4>";
    
    // Kiểm tra syntax
    $output = shell_exec('php -l ' . $file . ' 2>&1');
    
    if (strpos($output, 'No syntax errors') !== false) {
        echo "<div style='color:green;'>✅ $output</div>";
    } else {
        echo "<div style='color:red;'>❌ $output</div>";
    }
    
    // Hiển thị một phần code
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);
        echo "<pre style='background:#f5f5f5; padding:10px; max-height:200px; overflow:auto;'>";
        // Hiển thị 10 dòng cuối
        for ($i = max(0, count($lines) - 10); $i < count($lines); $i++) {
            echo htmlspecialchars(($i+1) . ": " . $lines[$i]) . "\n";
        }
        echo "</pre>";
    }
    echo "<hr>";
}

echo "<a href='index.php'>Quay lại trang chủ</a>";
?>