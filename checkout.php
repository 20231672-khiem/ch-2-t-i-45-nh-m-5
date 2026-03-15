<?php
session_start();
require_once '../includes/config.php';

// Nếu giỏ hàng trống, quay lại trang sản phẩm
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$grand_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $grand_total += $item['price'] * $item['quantity'];
}
?>
<?php
// pages/checkout.php - Sau khi đặt hàng thành công

// ... code xử lý đặt hàng ...

if ($order_success) {
    $order_id = mysqli_insert_id($conn);
    
    // 1. Tạo thông báo cho admin
    $admin_sql = "SELECT id FROM users WHERE role = 'admin'";
    $admin_result = mysqli_query($conn, $admin_sql);
    
    while ($admin = mysqli_fetch_assoc($admin_result)) {
        $notify_sql = "INSERT INTO notifications (user_id, type, title, message, link) VALUES (
            {$admin['id']},
            'order',
            'Đơn hàng mới #$order_code',
            'Khách hàng " . addslashes($customer_name) . " vừa đặt đơn hàng trị giá " . number_format($final_amount, 0, ',', '.') . "₫',
            'admin/order_detail.php?id=$order_id'
        )";
        mysqli_query($conn, $notify_sql);
    }
    
    // 2. Ghi log đơn hàng
    $log_sql = "INSERT INTO order_logs (order_id, action, new_status, note) VALUES (
        $order_id,
        'created',
        'pending',
        'Đơn hàng được tạo bởi khách hàng'
    )";
    mysqli_query($conn, $log_sql);
    
    // 3. Gửi email thông báo (nếu có)
    // sendOrderEmail($customer_email, $order_code);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - Phonestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #0d6efd !important; }
        .checkout-card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-checkout { background-color: #0d6efd; border: none; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="products.php"><i class="fas fa-mobile-alt"></i> Phonestore</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card checkout-card p-4">
                    <h4 class="fw-bold mb-4 text-primary"><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h4>
                    <form action="order-complete.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="customer_name" class="form-control" required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control" required placeholder="0901xxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Số nhà, tên đường, phường/xã..."></textarea>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-checkout btn-lg w-100 text-white">XÁC NHẬN ĐẶT HÀNG</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card checkout-card p-4 bg-white">
                    <h4 class="fw-bold mb-4">Đơn hàng của bạn</h4>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
                        <span class="fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="h5 fw-bold">Tổng cộng:</span>
                        <span class="h5 fw-bold text-danger"><?php echo number_format($grand_total, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>