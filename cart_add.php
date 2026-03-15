<?php
session_start();
require_once '../includes/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin sản phẩm từ DB
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if ($product) {
    // Nếu chưa có giỏ hàng thì tạo mới
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu sản phẩm đã có thì tăng số lượng, chưa có thì thêm mới
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

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit;