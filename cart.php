<?php
session_start();
require_once '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - Phonestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #0d6efd !important; }
        .cart-table img { width: 70px; border-radius: 5px; }
        .total-box { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="products.php"><i class="fas fa-mobile-alt"></i> Phonestore</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="fw-bold mb-4"><i class="fas fa-shopping-cart text-primary"></i> Giỏ hàng của bạn</h2>
        
        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive bg-white p-3 rounded shadow-sm">
                    <table class="table align-middle cart-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total = 0;
                            foreach ($_SESSION['cart'] as $id => $item): 
                                $total = $item['price'] * $item['quantity'];
                                $grand_total += $total;
                            ?>
                            <tr>
                                <td>
                                    <img src="../assets/images/products/<?php echo $item['image']; ?>" class="me-2">
                                    <strong><?php echo $item['name']; ?></strong>
                                </td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td class="text-danger fw-bold"><?php echo number_format($total, 0, ',', '.'); ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="total-box">
                    <h4>Tổng thanh toán</h4>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính:</span>
                        <span class="fw-bold"><?php echo number_format($grand_total, 0, ',', '.'); ?>đ</span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary btn-lg w-100">TIẾN HÀNH ĐẶT HÀNG</a>
                    <a href="products.php" class="btn btn-outline-secondary w-100 mt-2">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>Giỏ hàng trống!</h4>
                <a href="products.php" class="btn btn-primary mt-3">Quay lại mua sắm</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>