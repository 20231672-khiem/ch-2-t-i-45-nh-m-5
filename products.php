<?php
// pages/products.php hoàn chỉnh - FIXED LOGIC
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php';

// Kiểm tra kết nối
if (!$conn) {
    die("<div class='container mt-5'><div class='alert alert-danger'>❌ Lỗi kết nối database. Vui lòng kiểm tra file config.php</div></div>");
}

// Hàm tiện ích
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

// Lấy tham số
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Xây dựng điều kiện WHERE
$where_conditions = [];
$where_sql = "";

if ($category_id > 0) {
    $where_conditions[] = "category_id = $category_id";
}

if (!empty($search)) {
    $where_conditions[] = "name LIKE '%$search%'";
}

if (!empty($where_conditions)) {
    $where_sql = "WHERE " . implode(" AND ", $where_conditions);
}

// Lấy tổng số sản phẩm cho phân trang
$count_query = "SELECT COUNT(*) as total FROM products $where_sql";
$count_result = mysqli_query($conn, $count_query);

if (!$count_result) {
    echo "<div class='alert alert-danger'>Lỗi đếm sản phẩm: " . mysqli_error($conn) . "</div>";
    $total_products = 0;
} else {
    $count_data = mysqli_fetch_assoc($count_result);
    $total_products = $count_data['total'] ?? 0;
}

$total_pages = ($limit > 0) ? ceil($total_products / $limit) : 1;

// Lấy sản phẩm cho trang hiện tại
$products_query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  $where_sql 
                  ORDER BY p.id DESC 
                  LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $products_query);

// Kiểm tra lỗi query
$has_error = false;
$error_message = "";
if (!$result) {
    $has_error = true;
    $error_message = "Lỗi query: " . mysqli_error($conn);
}

// Lấy số sản phẩm thực tế trả về
$actual_products_count = $result ? mysqli_num_rows($result) : 0;

// Lấy danh mục cho sidebar
$categories_query = "SELECT c.*, COUNT(p.id) as product_count 
                    FROM categories c 
                    LEFT JOIN products p ON c.id = p.category_id 
                    GROUP BY c.id 
                    ORDER BY c.name";
$categories_result = mysqli_query($conn, $categories_query);

// Danh sách ảnh có sẵn (chỉ tên file, không có đuôi .jpg)
$available_images = [
    '128ip', 'a4g samsung', 'airpod', 'cáp sạc', 
    'ip14promax', 'ip15', 'iphone-15-pro-max', 'logo',
    'oppo find', 'oppo reno', 'realme gt5', 'samsung buds',
    'samsunggalaxy', 'samsungzford', 'sạc dự phòng',
    'xiomi12', 'xiomi13', 'ốp lưng'
];

// Kiểm tra ảnh no-image
$no_image_path = "../assets/images/no-image.jpg";
$no_image_exists = file_exists($no_image_path);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - PhoneStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        .product-price {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .product-title {
            font-size: 0.95rem;
            height: 48px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0, 123, 255, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .filter-sidebar {
            position: sticky;
            top: 20px;
        }
        .page-link.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .no-products {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-mobile-alt"></i> PhoneStore
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="../index.php">Trang chủ</a>
                <a class="nav-link active" href="products.php">Sản phẩm</a>
                <a class="nav-link" href="#">Giỏ hàng</a>
                <a class="nav-link" href="../admin/">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php"><i class="fas fa-home"></i> Trang chủ</a></li>
                <li class="breadcrumb-item active">Sản phẩm</li>
                <?php if ($category_id > 0): 
                    $cat_name_query = mysqli_query($conn, "SELECT name FROM categories WHERE id = $category_id");
                    if ($cat_name = mysqli_fetch_assoc($cat_name_query)): ?>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($cat_name['name']); ?></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <div class="container my-4">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3 mb-4">
                <div class="card filter-sidebar">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter"></i> Bộ lọc
                    </div>
                    <div class="card-body">
                        <!-- Tìm kiếm -->
                        <form method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Tìm sản phẩm..." 
                                       value="<?php echo htmlspecialchars($search); ?>">
                                <?php if ($category_id > 0): ?>
                                    <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                                <?php endif; ?>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Danh mục -->
                        <h6><i class="fas fa-list"></i> Danh mục</h6>
                        <div class="list-group mb-4">
                            <a href="products.php?search=<?php echo urlencode($search); ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo !$category_id ? 'active' : ''; ?>">
                                Tất cả danh mục
                                <span class="badge bg-primary rounded-pill"><?php echo $total_products; ?></span>
                            </a>
                            <?php if ($categories_result && mysqli_num_rows($categories_result) > 0): ?>
                                <?php while($cat = mysqli_fetch_assoc($categories_result)): ?>
                                <a href="products.php?category=<?php echo $cat['id']; ?>&search=<?php echo urlencode($search); ?>" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $category_id == $cat['id'] ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                    <span class="badge bg-secondary rounded-pill"><?php echo $cat['product_count']; ?></span>
                                </a>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="list-group-item">Không có danh mục</div>
                            <?php endif; ?>
                        </div>

                        <?php if ($category_id > 0 || !empty($search)): ?>
                        <div class="d-grid">
                            <a href="products.php" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-times"></i> Xóa bộ lọc
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="mb-1">
                            <?php 
                            if ($category_id > 0) {
                                $cat_query = mysqli_query($conn, "SELECT name FROM categories WHERE id = $category_id");
                                if ($cat = mysqli_fetch_assoc($cat_query)) {
                                    echo htmlspecialchars($cat['name']);
                                } else {
                                    echo 'Sản phẩm';
                                }
                            } elseif (!empty($search)) {
                                echo 'Tìm kiếm: "' . htmlspecialchars($search) . '"';
                            } else {
                                echo 'Tất cả sản phẩm';
                            }
                            ?>
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-box"></i> 
                            Hiển thị <?php echo ($offset + 1); ?>-<?php echo min($offset + $limit, $total_products); ?> 
                            của <?php echo $total_products; ?> sản phẩm
                        </p>
                    </div>
                </div>

                <?php if ($has_error): ?>
                <!-- Hiển thị lỗi nếu có -->
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                </div>
                <?php endif; ?>

                <!-- Products Grid -->
                <?php if ($result && $actual_products_count > 0): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php while($product = mysqli_fetch_assoc($result)): 
                        // Xử lý ảnh
                        $image_name = $product['image'] ?? '';
                        $image_path = "";
                        $image_found = false;
                        
                        if (!empty($image_name)) {
                            $image_path = "../assets/images/products/" . $image_name;
                            if (file_exists($image_path)) {
                                $image_found = true;
                            }
                        }
                        
                        // Nếu không tìm thấy ảnh, thử tìm ảnh phù hợp
                        if (!$image_found) {
                            $product_name_lower = strtolower($product['name']);
                            foreach ($available_images as $img) {
                                $img_lower = strtolower($img);
                                if (strpos($product_name_lower, 'iphone') !== false && strpos($img_lower, 'iphone') !== false) {
                                    $image_path = "../assets/images/products/" . $img . '.jpg';
                                    if (file_exists($image_path)) {
                                        $image_found = true;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        // Nếu vẫn không có ảnh, dùng ảnh mặc định
                        if (!$image_found) {
                            $image_path = $no_image_exists ? $no_image_path : 'https://via.placeholder.com/300x200?text=No+Image';
                        }
                        
                        // Xử lý giá
                        $price = $product['price'] ?? 0;
                        $stock = $product['stock'] ?? 0;
                        $category_name = $product['category_name'] ?? 'Chưa phân loại';
                    ?>
                    <div class="col">
                        <div class="card product-card">
                            <div class="product-img-container">
                                <img src="<?php echo $image_path; ?>" 
                                     class="product-img" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='<?php echo $no_image_exists ? $no_image_path : 'https://via.placeholder.com/300x200?text=No+Image'; ?>'">
                                
                                <span class="category-badge"><?php echo substr($category_name, 0, 15); ?></span>
                                
                                <?php if ($stock > 0): ?>
                                <span class="stock-badge">Còn <?php echo $stock; ?></span>
                                <?php else: ?>
                                <span class="stock-badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title product-title mb-2">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h6>
                                
                                <div class="mt-auto">
                                    <p class="card-text mb-2">
                                        <?php if ($price > 0): ?>
                                        <span class="product-price"><?php echo formatPrice($price); ?></span>
                                        <?php else: ?>
                                        <span class="text-primary fw-bold">Liên hệ</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                                        </a>
                                        
                                        
                                <?php if ($price > 0 && $stock > 0): ?>
                                        <a href="cart-process.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-cart-plus me-1"></i> Thêm giỏ hàng
                                        </a>
                                        <?php elseif ($stock == 0): ?>
                                          <button class="btn btn-secondary btn-sm" disabled>
                                           <i class="fas fa-times me-1"></i> Hết hàng
                                          </button>
                                   <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <!-- Previous -->
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&category=<?php echo $category_id; ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == 1 || $i == $total_pages || ($i >= $page-1 && $i <= $page+1)): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $category_id; ?>&search=<?php echo urlencode($search); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php elseif ($i == 2 && $page > 3): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            <?php elseif ($i == $total_pages-1 && $page < $total_pages-2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&category=<?php echo $category_id; ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <!-- No products found -->
                <div class="no-products bg-light rounded p-5 text-center">
                    <div>
                        <i class="fas fa-search fa-4x text-muted mb-4"></i>
                        <h4 class="mb-3">Không tìm thấy sản phẩm nào</h4>
                        <p class="text-muted mb-4">
                            <?php if (!empty($search) || $category_id > 0): ?>
                            Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác
                            <?php else: ?>
                            Hiện chưa có sản phẩm nào trong cửa hàng
                            <?php endif; ?>
                        </p>
                        <a href="products.php" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i> Xem tất cả sản phẩm
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Debug info (chỉ hiển thị khi cần) -->
                <?php if (isset($_GET['debug'])): ?>
                <div class="mt-4 p-3 border rounded bg-light">
                    <h6><i class="fas fa-bug"></i> Debug Info:</h6>
                    <p><strong>Tổng sản phẩm:</strong> <?php echo $total_products; ?></p>
                    <p><strong>Sản phẩm hiển thị:</strong> <?php echo $actual_products_count; ?></p>
                    <p><strong>Query:</strong> <small><?php echo htmlspecialchars($products_query); ?></small></p>
                    <p><strong>Where SQL:</strong> <?php echo $where_sql ?: 'Không có điều kiện'; ?></p>
                </div>
                <?php endif; ?>

                <!-- Quick Links -->
                <div class="mt-4 pt-3 border-top text-center">
                    <small class="text-muted">
                        <a href="check_images.php" class="text-decoration-none me-3">
                            <i class="fas fa-images"></i> Kiểm tra ảnh
                        </a>
                        <a href="../admin/" class="text-decoration-none me-3">
                            <i class="fas fa-cog"></i> Quản trị
                        </a>
                        <a href="products.php?debug=1" class="text-decoration-none">
                            <i class="fas fa-bug"></i> Debug
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-mobile-alt"></i> PhoneStore</h5>
                    <p>Điện thoại & phụ kiện chính hãng</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> All rights reserved.</p>
                    <p class="mb-0">Database: phonestore_db | <?php echo $total_products; ?> sản phẩm</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thêm vào giỏ hàng
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const productCard = this.closest('.product-card');
                    const productName = productCard.querySelector('.card-title').textContent;
                    
                    // Hiển thị thông báo
                    alert('Đã thêm "' + productName.trim() + '" vào giỏ hàng!');
                    
                    // Ở đây bạn có thể thêm code gửi AJAX request đến server
                });
            });
        });
    </script>
</body>
</html>