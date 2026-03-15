<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khuyến mãi - PhoneStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Đồng nhất phông nền với trang products.php */
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .navbar { background-color: #0d6efd !important; }
        
        .promo-card { 
            border: none; 
            border-radius: 12px; 
            overflow: hidden; 
            transition: transform 0.3s; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: #fff;
        }
        .promo-card:hover { transform: translateY(-8px); }
        
        /* Cố định chiều cao ảnh để không bị lệch khung */
        .promo-img {
            height: 250px;
            object-fit: cover;
            background: #e9ecef;
        }
        
        .badge-hot { background-color: #ffc107; color: black; font-weight: bold; }
        footer { background: #212529; color: white; padding: 30px 0; margin-top: 50px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php"><i class="fas fa-mobile-alt"></i> PhoneStore</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="products.php">Sản phẩm</a>
                <a class="nav-link active" href="promotions.php">Khuyến mãi</a>
                <a class="nav-link" href="about.php">Giới thiệu</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase">Chương trình ưu đãi</h2>
            <div class="bg-primary mx-auto" style="width: 60px; height: 4px; border-radius: 2px;"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card promo-card">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=800&q=80" 
                         class="card-img-top promo-img" 
                         alt="Flash Sale"
                         onerror="this.src='https://via.placeholder.com/800x400?text=PhoneStore+Promotion'">
                    <div class="card-body">
                        <span class="badge badge-hot mb-2"><i class="fas fa-bolt"></i> GIẢM SỐC</span>
                        <h4 class="card-title fw-bold">Siêu hội Samsung - Giảm đến 30%</h4>
                        <p class="text-muted">Áp dụng cho các dòng Galaxy S23 và Z Fold5 khi thanh toán qua thẻ.</p>
                        <a href="products.php" class="btn btn-primary w-100">Săn deal ngay</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card promo-card">
                    <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=800&q=80" 
                         class="card-img-top promo-img" 
                         alt="Phụ kiện"
                         onerror="this.src='https://via.placeholder.com/800x400?text=PhoneStore+Combo'">
                    <div class="card-body">
                        <span class="badge bg-success mb-2 text-white">COMBO TIẾT KIỆM</span>
                        <h4 class="card-title fw-bold">Mua iPhone - Tặng trọn bộ phụ kiện</h4>
                        <p class="text-muted">Tặng ngay ốp lưng, kính cường lực và củ sạc nhanh khi mua iPhone 15.</p>
                        <a href="products.php" class="btn btn-primary w-100">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center text-white mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 PhoneStore - Điện thoại & Phụ kiện chính hãng</p>
        </div>
    </footer>
</body>
</html>