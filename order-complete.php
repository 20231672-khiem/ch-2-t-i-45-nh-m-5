<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    // 1. Lấy thông tin từ Form
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $order_date = date('Y-m-d H:i:s');
    
    // Tính tổng tiền
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // 2. Lưu thông tin đơn hàng vào DB (Lưu ý: Bạn cần có bảng orders)
    // Nếu bạn chưa có bảng, có thể bỏ qua bước mysqli_query này để test giao diện
    $query = "INSERT INTO orders (customer_name, phone, address, total_amount, order_date) 
              VALUES ('$name', '$phone', '$address', '$total_amount', '$order_date')";
    
    if (mysqli_query($conn, $query)) {
        // 3. Xóa giỏ hàng sau khi đặt thành công
        unset($_SESSION['cart']);
    }
} else {
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công - Phonestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #0d6efd !important; }
        .success-box { background: white; padding: 50px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container"><a class="navbar-brand fw-bold" href="products.php">Phonestore</a></div>
    </nav>

    <div class="container text-center mt-5">
        <div class="success-box mx-auto" style="max-width: 600px;">
            <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
            <h2 class="fw-bold text-primary">ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p class="lead mt-3">Cảm ơn bạn đã tin tưởng <strong>Phonestore</strong>. Chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng sớm nhất.</p>
            <hr>
            <p class="text-muted small">Mã đơn hàng: #PS<?php echo time(); ?></p>
            <a href="products.php" class="btn btn-primary btn-lg px-5 mt-3">TIẾP TỤC MUA SẮM</a>
        </div>
    </div>
</body>
</html>