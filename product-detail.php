<?php
// 1. Kết nối cơ sở dữ liệu
require_once '../includes/config.php';

// 2. Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 3. Truy vấn lấy thông tin sản phẩm và tên danh mục
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

// 4. Nếu không tìm thấy sản phẩm, quay lại trang chủ
if (!$product) {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - PhoneStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Đồng nhất Font chữ và Màu nền chính */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Navbar giống hệt trang sản phẩm */
        .navbar {
            background-color: #0d6efd !important;
        }

        /* Breadcrumb tinh chỉnh */
        .breadcrumb-item a {
            color: #0d6efd;
            text-decoration: none;
        }

        /* Card chi tiết sản phẩm */
        .product-detail-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fff;
        }

        .product-img-container {
            padding: 20px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s;
        }

        .product-img:hover {
            transform: scale(1.02);
        }

        .product-price {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.8rem;
        }

        .description-box {
            background: #fdfdfd;
            padding: 15px;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
        }

        /* Nút bấm đồng bộ */
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        footer {
            background-color: #212529;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-mobile-alt"></i> PhoneStore
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">Trang chủ</a>
                <a class="nav-link active" href="products.php">Sản phẩm</a>
                <a class="nav-link" href="#">Giỏ hàng</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="products.php">Sản phẩm</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['category_name']); ?></li>
            </ol>
        </nav>

        <div class="card product-detail-card">
            <div class="row g-0">
                <div class="col-md-5 product-img-container border-end">
                    <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                         class="product-img" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                </div>
                
                <div class="col-md-7 p-4 p-lg-5">
                    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-price mb-4"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                    
                    <div class="mb-4">
                        <p><strong><i class="fas fa-tag"></i> Danh mục:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                        <p><strong><i class="fas fa-check-circle"></i> Trạng thái:</strong> 
                            <?php echo ($product['stock'] > 0) ? '<span class="text-success">Còn '. $product['stock'] .' sản phẩm</span>' : '<span class="text-danger">Hết hàng</span>'; ?>
                        </p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-secondary">Mô tả sản phẩm</h6>
                        <div class="description-box">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </div>
                    </div>

                    <div class="d-grid gap-3 d-md-flex">
                        <?php if($product['stock'] > 0): ?>
                            <button class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                            </button>
                        <?php endif; ?>
                        <a href="products.php" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-undo me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> PhoneStore - Điện thoại & Phụ kiện chính hãng</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>