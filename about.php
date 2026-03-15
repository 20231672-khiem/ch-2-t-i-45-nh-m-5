<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - PhoneStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Đồng nhất phông nền xám nhạt và font chữ của PhoneStore */
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, sans-serif; color: #333; }
        .navbar { background-color: #0d6efd !important; }
        
        /* Banner chính */
        .hero-about {
            background: linear-gradient(rgba(13, 110, 253, 0.85), rgba(13, 110, 253, 0.85)), 
                        url('https://images.unsplash.com/photo-1556740734-7f196f79144c?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        /* Khối thống kê */
        .stat-card {
            background: #fff;
            border: none;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number { font-size: 2.5rem; font-weight: 800; color: #0d6efd; }

        /* Khối cam kết (Dịch vụ) */
        .service-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .service-icon {
            font-size: 35px;
            color: #0d6efd;
            margin-right: 20px;
            width: 60px;
            text-align: center;
        }

        footer { background: #212529; color: white; padding: 40px 0; margin-top: 60px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php"><i class="fas fa-mobile-alt"></i> PhoneStore</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="products.php">Sản phẩm</a>
                <a class="nav-link" href="promotions.php">Khuyến mãi</a>
                <a class="nav-link active" href="about.php">Giới thiệu</a>
            </div>
        </div>
    </nav>

    <header class="hero-about">
        <div class="container">
            <h1 class="display-4 fw-bold">Chào mừng đến với PhoneStore</h1>
            <p class="lead">Đơn vị bán lẻ điện thoại và phụ kiện chính hãng uy tín nhất từ 2025</p>
        </div>
    </header>

    <div class="container my-5">
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">10K+</div>
                    <div class="text-muted">Khách hàng tin dùng</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="text-muted">Cửa hàng toàn quốc</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="text-muted">Hàng chính hãng</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="text-muted">Hỗ trợ tận tâm</div>
                </div>
            </div>
        </div>

        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4 text-primary">PhoneStore - Chất lượng tạo nên niềm tin</h2>
                <p>Chúng tôi hiểu rằng điện thoại không chỉ là công cụ liên lạc, mà còn là người bạn đồng hành trong cuộc sống và công việc. PhoneStore cam kết mang đến những thiết bị đỉnh cao nhất với mức giá cạnh tranh nhất thị trường.</p>
                
                <div class="service-box">
                    <div class="service-icon"><i class="fas fa-shipping-fast"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0">Giao hàng siêu tốc</h6>
                        <small class="text-muted">Nhận máy chỉ trong 2h tại nội thành.</small>
                    </div>
                </div>

                <div class="service-box">
                    <div class="service-icon"><i class="fas fa-tools"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0">Bảo hành chính hãng</h6>
                        <small class="text-muted">Lỗi 1 đổi 1 trong 30 ngày đầu tiên.</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1556742502-ec7c0e9f34b1?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-3 shadow-lg" alt="Showroom">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold mb-3 text-center">Tìm cửa hàng gần bạn nhất</h4>
                <div class="ratio ratio-21x9 rounded-3 overflow-hidden shadow-sm">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.92440391583!2d105.81645417592471!3d21.035710587537632!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab133221cf6f%3A0x67396a8471900384!2zSOG7jWMgdmnhu4duIE7hu5lpIHbhu6U!5e0!3m2!1svi!2s!4v1700000000000" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2026 <strong>PhoneStore</strong> - Hệ thống bán lẻ công nghệ chính hãng.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>