<?php
// index.php - Phiên bản hoàn chỉnh không lỗi
session_start();
require_once 'includes/config.php';

// Lấy sản phẩm từ database
$sql = "SELECT * FROM products WHERE status = 1 LIMIT 8";
$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi kết nối
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhoneStore - Cửa hàng điện thoại</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 80px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #0d6efd !important;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        .price {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .old-price {
            text-decoration: line-through;
            color: #888;
            font-size: 0.9rem;
        }
        .btn-cart {
            width: 100%;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .sale-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            z-index: 10;
        }
        .category-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .category-card:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 50px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- HEADER NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-mobile-alt"></i> Phonestore
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/products.php">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/promotions.php">Khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/about.php">Giới thiệu</a>
                    </li>
                </ul>
                
                <!-- Right Side -->
                <div class="d-flex">
                    <!-- Search -->
                    <form class="d-flex me-3">
                        <input class="form-control me-2" type="search" placeholder="Tìm điện thoại...">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <!-- User Menu -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown me-3">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="pages/profile.php">Tài khoản</a></li>
                                <li><a class="dropdown-item" href="pages/orders.php">Đơn hàng</a></li>
                                <li><a class="dropdown-item" href="pages/logout.php">Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="pages/login.php" class="btn btn-outline-primary me-2">Đăng nhập</a>
                        <a href="pages/register.php" class="btn btn-primary">Đăng ký</a>
                    <?php endif; ?>
                    
                    <!-- Cart -->
                    <a href="pages/cart.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">CHÀO MỪNG ĐẾN PHONESTORE</h1>
            <p class="lead">Cửa hàng điện thoại uy tín - Giá tốt nhất thị trường</p>
            <a href="pages/products.php" class="btn btn-light btn-lg mt-3">
                <i class="fas fa-shopping-bag"></i> MUA SẮM NGAY
            </a>
        </div>
    </section>

    <!-- CATEGORIES -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">DANH MỤC SẢN PHẨM</h2>
            <div class="row">
                <div class="col-md-3 col-6 mb-4">
                    <a href="pages/products.php?category=iphone" class="text-decoration-none">
                        <div class="category-card">
                            <i class="fab fa-apple fa-3x text-dark mb-3"></i>
                            <h5>iPhone</h5>
                            <p class="text-muted">Sản phẩm chính hãng</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <a href="pages/products.php?category=samsung" class="text-decoration-none">
                        <div class="category-card">
                            <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                            <h5>Samsung</h5>
                            <p class="text-muted">Galaxy series</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <a href="pages/products.php?category=xiaomi" class="text-decoration-none">
                        <div class="category-card">
                            <i class="fas fa-phone fa-3x text-danger mb-3"></i>
                            <h5>Xiaomi</h5>
                            <p class="text-muted">Redmi & Mi series</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <a href="pages/products.php?category=oppo" class="text-decoration-none">
                        <div class="category-card">
                            <i class="fas fa-mobile fa-3x text-success mb-3"></i>
                            <h5>OPPO</h5>
                            <p class="text-muted">Reno series</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">SẢN PHẨM NỔI BẬT</h2>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row">
                <?php 
                while ($row = mysqli_fetch_assoc($result)) {
                    $current_price = $row['sale_price'] ?: $row['price'];
                    $discount_percent = 0;
                    
                    if ($row['sale_price'] && $row['price'] > 0) {
                        $discount_percent = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    }
                    
                    // Xử lý hình ảnh
                    $image_path = "assets/images/products/" . $row['image'];
                    if (file_exists($image_path) && !empty($row['image'])) {
                        $image_url = $image_path;
                    } else {
                        // Tạo placeholder
                        $color = '3498db';
                        if (stripos($row['name'], 'samsung') !== false) $color = '2ecc71';
                        if (stripos($row['name'], 'xiaomi') !== false) $color = 'e74c3c';
                        if (stripos($row['name'], 'airpods') !== false) $color = '1abc9c';
                        
                        $product_name = urlencode(substr($row['name'], 0, 20));
                        $image_url = "https://via.placeholder.com/400x300/" . $color . "/ffffff?text=" . $product_name;
                    }
                ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card product-card h-100">
                        <?php if ($row['sale_price']): ?>
                        <div class="sale-badge">-<?php echo $discount_percent; ?>%</div>
                        <?php endif; ?>
                        
                        <img src="<?php echo $image_url; ?>" 
                             class="card-img-top product-img" 
                             alt="<?php echo htmlspecialchars($row['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/400x300/cccccc/333333?text=<?php echo urlencode(substr($row['name'], 0, 15)); ?>'">
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title" style="min-height: 48px;"><?php echo htmlspecialchars($row['name']); ?></h6>
                            
                            <div class="mb-2">
                                <div class="price"><?php echo number_format($current_price, 0, ',', '.'); ?>₫</div>
                                <?php if ($row['sale_price']): ?>
                                <div class="old-price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</div>
                                <?php endif; ?>
                            </div>
                            
                            <p class="text-muted small mb-3">
                                <?php if ($row['stock'] > 0): ?>
                                <i class="fas fa-check-circle text-success"></i> Còn <?php echo $row['stock']; ?> sản phẩm
                                <?php else: ?>
                                <i class="fas fa-times-circle text-danger"></i> Hết hàng
                                <?php endif; ?>
                            </p>
                            
                            <div class="mt-auto">
                                <button class="btn btn-primary btn-cart" onclick="addToCart(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-cart-plus"></i> Thêm giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có sản phẩm nào. Vui lòng thêm sản phẩm vào database!
                </div>
                <a href="test_db.php" class="btn btn-primary">Kiểm tra Database</a>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="pages/products.php" class="btn btn-outline-primary btn-lg">
                    Xem tất cả sản phẩm <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- PROMOTIONS -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">KHUYẾN MÃI HOT</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body p-5 text-center">
                            <h3 class="card-title">GIẢM 30%</h3>
                            <p class="card-text">Mua iPhone 15 tặng AirPods</p>
                            <p class="small">Áp dụng đến 31/12/2024</p>
                            <a href="#" class="btn btn-dark">Xem ngay</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body p-5 text-center">
                            <h3 class="card-title">TRẢ GÓP 0%</h3>
                            <p class="card-text">Mua Samsung trả góp không lãi suất</p>
                            <p class="small">Qua thẻ tín dụng</p>
                            <a href="#" class="btn btn-light">Xem ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>PhoneStore</h4>
                    <p>Địa chỉ: HUD Vân Canh, Hoài Đức, Hà Nội</p>
                    <p>Email: contact@phonestore.vn</p>
                    <p>Hotline: 0987.654.321</p>
                </div>
                <div class="col-md-2">
                    <h5>Về chúng tôi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50">Giới thiệu</a></li>
                        <li><a href="#" class="text-white-50">Tin tức</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Chính sách</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50">Bảo hành</a></li>
                        <li><a href="#" class="text-white-50">Đổi trả</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kết nối với chúng tôi</h5>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a href="#" class="text-white me-3">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="fab fa-tiktok fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="bg-white my-4">
            <div class="text-center">
                <p>&copy; 2026 PhoneStore. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Thêm vào giỏ hàng
        function addToCart(productId) {
            alert('Đã thêm sản phẩm ID: ' + productId + ' vào giỏ hàng!');
        }
        
        // Xử lý lỗi hình ảnh
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.src = 'https://via.placeholder.com/400x300/cccccc/333333?text=Phone+Store';
                });
            });
        });
    </script>
</body>
</html>