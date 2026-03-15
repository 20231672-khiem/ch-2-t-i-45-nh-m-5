<?php
// includes/header.php
require_once 'config.php';
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
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 80px; /* For fixed navbar */
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .product-img-container {
            height: 220px;
            overflow: hidden;
            position: relative;
            background: #f8f9fa;
        }
        
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .badge-sale {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--danger-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            z-index: 1;
        }
        
        .price {
            color: var(--danger-color);
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .old-price {
            text-decoration: line-through;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .discount-percent {
            background: #ffe6e6;
            color: var(--danger-color);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .category-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('assets/images/banner-hero.jpg') center/cover no-repeat;
            color: white;
            padding: 120px 0;
            margin-bottom: 60px;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .feature-icon i {
            font-size: 1.8rem;
            color: white;
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            margin-top: 80px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .product-img-container {
                height: 180px;
            }
            
            .hero-section {
                padding: 80px 0;
            }
        }
    </style>
</head>
<body>
    <!-- NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-mobile-alt me-2"></i>PhoneStore
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Main Menu -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/products.php">
                            <i class="fas fa-shopping-bag me-1"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-percent me-1"></i>Khuyến mãi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-info-circle me-1"></i>Giới thiệu
                        </a>
                    </li>
                </ul>
                
                <!-- Right Menu -->
                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <form class="d-flex me-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm điện thoại...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- User Menu -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo $_SESSION['username']; ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pages/profile.php">
                                <i class="fas fa-user me-2"></i>Tài khoản
                            </a></li>
                            <li><a class="dropdown-item" href="pages/orders.php">
                                <i class="fas fa-clipboard-list me-2"></i>Đơn hàng
                            </a></li>
                            <li><a class="dropdown-item" href="pages/wishlist.php">
                                <i class="fas fa-heart me-2"></i>Yêu thích
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li><a class="dropdown-item" href="admin/index.php">
                                <i class="fas fa-crown me-2"></i>Admin Panel
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="pages/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <a href="pages/login.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                    </a>
                    <a href="pages/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Đăng ký
                    </a>
                    <?php endif; ?>
                    
                    <!-- Cart -->
                    <?php
                    $cart_count = 0;
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT SUM(quantity) as total FROM carts WHERE user_id = $user_id";
                        $result = mysqli_query($conn, $sql);
                        $cart_count = mysqli_fetch_assoc($result)['total'] ?: 0;
                    }
                    ?>
                    <a href="pages/cart.php" class="btn btn-primary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $cart_count; ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>