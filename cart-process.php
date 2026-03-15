<?php
session_start();
require_once '../includes/config.php'; // Đảm bảo đường dẫn này đúng với project của bạn

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn lấy thông tin sản phẩm từ DB
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if ($product) {
    // Nếu chưa có giỏ hàng, khởi tạo mảng rỗng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu sản phẩm đã có, tăng số lượng. Nếu chưa, thêm mới vào mảng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
    }
}

// Sau khi thêm xong, chuyển hướng về trang giỏ hàng
header("Location: cart.php");
exit();
?>