-- ============================================
-- TẠO CƠ SỞ DỮ LIỆU VÀ BẢNG
-- ============================================

CREATE DATABASE IF NOT EXISTS phonestore_db;
USE phonestore_db;

-- 1. BẢNG NGƯỜI DÙNG
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. BẢNG DANH MỤC
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    image VARCHAR(255) DEFAULT 'default-category.jpg',
    description TEXT,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 3. BẢNG SẢN PHẨM
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    category_id INT NOT NULL,
    brand VARCHAR(50),
    price DECIMAL(12, 3) NOT NULL,
    sale_price DECIMAL(12, 3),
    image VARCHAR(255) NOT NULL,
    gallery TEXT, -- JSON chứa nhiều hình ảnh
    description TEXT,
    specifications JSON, -- Lưu thông số kỹ thuật dạng JSON
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4. BẢNG ĐƠN HÀNG
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) UNIQUE NOT NULL,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    total_amount DECIMAL(12, 3) NOT NULL,
    discount_amount DECIMAL(12, 3) DEFAULT 0,
    shipping_fee DECIMAL(10, 3) DEFAULT 0,
    final_amount DECIMAL(12, 3) NOT NULL,
    payment_method ENUM('cod', 'momo', 'banking', 'vnpay') DEFAULT 'cod',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 5. BẢNG CHI TIẾT ĐƠN HÀNG
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_price DECIMAL(12, 3) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(12, 3) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 6. BẢNG GIỎ HÀNG
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- 7. BẢNG YÊU THÍCH
CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- 8. BẢNG ĐÁNH GIÁ
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    comment TEXT,
    images JSON, -- JSON chứa đường dẫn hình ảnh đánh giá
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 9. BẢNG TIN TỨC
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    content LONGTEXT,
    excerpt TEXT,
    image VARCHAR(255),
    author VARCHAR(100),
    views INT DEFAULT 0,
    status BOOLEAN DEFAULT TRUE,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 10. BẢNG BANNER
CREATE TABLE IF NOT EXISTS banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    image VARCHAR(255) NOT NULL,
    link VARCHAR(500),
    position VARCHAR(50) NOT NULL,
    sort_order INT DEFAULT 0,
    status BOOLEAN DEFAULT TRUE,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 11. BẢNG MÃ GIẢM GIÁ
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percent', 'fixed') NOT NULL,
    discount_value DECIMAL(10, 3) NOT NULL,
    min_order_amount DECIMAL(10, 3) DEFAULT 0,
    max_discount_amount DECIMAL(10, 3),
    usage_limit INT DEFAULT 0,
    used_count INT DEFAULT 0,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. BẢNG LIÊN HỆ
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('pending', 'read', 'replied', 'closed') DEFAULT 'pending',
    reply_message TEXT,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 13. BẢNG CẤU HÌNH
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE NOT NULL,
    `value` TEXT,
    `group` VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- THÊM DỮ LIỆU MẪU ĐẦY ĐỦ
-- ============================================

-- 1. THÊM NGƯỜI DÙNG (Mật khẩu: password123)
INSERT INTO users (username, email, password, full_name, phone, address, role, avatar) VALUES
-- Admin
('admin', 'admin@phonestore.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn Admin', '0987654321', '123 Nguyễn Văn Linh, Quận 7, TP.HCM', 'admin', 'admin-avatar.jpg'),
-- Users
('minhnguyen', 'minh@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn Minh', '0912345678', '456 Lê Lợi, Quận 1, TP.HCM', 'user', 'user1-avatar.jpg'),
('thanhthao', 'thao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị Thanh Thảo', '0923456789', '789 Pasteur, Quận 3, TP.HCM', 'user', 'user2-avatar.jpg'),
('hoangnam', 'nam@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Hoàng Nam', '0934567890', '321 Cách Mạng Tháng 8, Quận 10, TP.HCM', 'user', 'user3-avatar.jpg');

-- 2. THÊM DANH MỤC
INSERT INTO categories (name, slug, image, description) VALUES
-- Điện thoại
('iPhone', 'iphone', 'category-iphone.jpg', 'Điện thoại iPhone chính hãng'),
('Samsung', 'samsung', 'category-samsung.jpg', 'Điện thoại Samsung Galaxy'),
('Xiaomi', 'xiaomi', 'category-xiaomi.jpg', 'Điện thoại Xiaomi Redmi'),
('OPPO', 'oppo', 'category-oppo.jpg', 'Điện thoại OPPO Reno'),
('Realme', 'realme', 'category-realme.jpg', 'Điện thoại Realme GT'),
-- Phụ kiện
('Tai nghe', 'tai-nghe', 'category-headphone.jpg', 'Tai nghe Bluetooth, có dây'),
('Sạc dự phòng', 'sac-du-phong', 'category-powerbank.jpg', 'Pin sạc dự phòng'),
('Ốp lưng', 'op-lung', 'category-case.jpg', 'Ốp lưng điện thoại'),
('Cáp sạc', 'cap-sac', 'category-cable.jpg', 'Cáp sạc nhanh');

-- 3. THÊM SẢN PHẨM (ĐIỆN THOẠI)
INSERT INTO products (name, slug, category_id, brand, price, sale_price, image, gallery, description, stock, featured) VALUES
-- iPhone
('iPhone 15 Pro Max 256GB', 'iphone-15-pro-max-256gb', 1, 'Apple', 32990000, 29990000, 'iphone-15-pro-max.jpg', '["iphone-15-pro-max-1.jpg", "iphone-15-pro-max-2.jpg", "iphone-15-pro-max-3.jpg"]', 'iPhone 15 Pro Max 256GB - Titan tự nhiên - Chính hãng VN/A', 50, 1),
('iPhone 15 Pro 128GB', 'iphone-15-pro-128gb', 1, 'Apple', 28990000, 26990000, 'iphone-15-pro.jpg', '["iphone-15-pro-1.jpg", "iphone-15-pro-2.jpg"]', 'iPhone 15 Pro 128GB - Titan xanh - Chính hãng VN/A', 30, 1),
('iPhone 15 Plus 128GB', 'iphone-15-plus-128gb', 1, 'Apple', 24990000, 22990000, 'iphone-15-plus.jpg', '["iphone-15-plus-1.jpg"]', 'iPhone 15 Plus 128GB - Màu hồng - Chính hãng VN/A', 40, 0),
('iPhone 14 Pro Max 128GB', 'iphone-14-pro-max-128gb', 1, 'Apple', 27990000, 23990000, 'iphone-14-pro-max.jpg', '["iphone-14-pro-max-1.jpg", "iphone-14-pro-max-2.jpg"]', 'iPhone 14 Pro Max 128GB - Màu tím - Chính hãng VN/A', 25, 0),

-- Samsung
('Samsung Galaxy S23 Ultra 12GB/256GB', 'samsung-s23-ultra-12gb-256gb', 2, 'Samsung', 28990000, 25990000, 'samsung-s23-ultra.jpg', '["samsung-s23-ultra-1.jpg", "samsung-s23-ultra-2.jpg", "samsung-s23-ultra-3.jpg"]', 'Samsung Galaxy S23 Ultra 5G - Camera 200MP - Bút S-Pen', 35, 1),
('Samsung Galaxy Z Fold5 12GB/512GB', 'samsung-z-fold5-12gb-512gb', 2, 'Samsung', 39990000, 36990000, 'samsung-z-fold5.jpg', '["samsung-z-fold5-1.jpg", "samsung-z-fold5-2.jpg"]', 'Samsung Galaxy Z Fold5 - Màn hình gập - Snapdragon 8 Gen 2', 15, 1),
('Samsung Galaxy A54 5G 8GB/128GB', 'samsung-a54-5g-8gb-128gb', 2, 'Samsung', 8990000, 7990000, 'samsung-a54.jpg', '["samsung-a54-1.jpg"]', 'Samsung Galaxy A54 5G - Màn hình 120Hz - Camera 50MP', 60, 0),

-- Xiaomi
('Xiaomi 13 Pro 12GB/256GB', 'xiaomi-13-pro-12gb-256gb', 3, 'Xiaomi', 19990000, 17990000, 'xiaomi-13-pro.jpg', '["xiaomi-13-pro-1.jpg", "xiaomi-13-pro-2.jpg"]', 'Xiaomi 13 Pro - Camera Leica - Snapdragon 8 Gen 2', 45, 1),
('Xiaomi Redmi Note 12 Pro 8GB/128GB', 'xiaomi-redmi-note-12-pro', 3, 'Xiaomi', 7990000, 6990000, 'redmi-note-12-pro.jpg', '["redmi-note-12-pro-1.jpg"]', 'Redmi Note 12 Pro - Màn hình AMOLED 120Hz - Sạc 67W', 80, 0),

-- OPPO
('OPPO Find N3 Flip 12GB/256GB', 'oppo-find-n3-flip-12gb-256gb', 4, 'OPPO', 21990000, 19990000, 'oppo-n3-flip.jpg', '["oppo-n3-flip-1.jpg"]', 'OPPO Find N3 Flip - Màn hình gập - Camera Hasselblad', 20, 1),
('OPPO Reno10 Pro 5G 12GB/256GB', 'oppo-reno10-pro-5g', 4, 'OPPO', 12990000, 11990000, 'oppo-reno10-pro.jpg', '["oppo-reno10-pro-1.jpg"]', 'OPPO Reno10 Pro 5G - Camera chân dung 32MP', 50, 0),

-- Realme
('Realme GT5 240W 16GB/1TB', 'realme-gt5-240w-16gb-1tb', 5, 'Realme', 16990000, 15990000, 'realme-gt5.jpg', '["realme-gt5-1.jpg"]', 'Realme GT5 - Sạc nhanh 240W - Snapdragon 8 Gen 2', 30, 0),

-- Phụ kiện
('AirPods Pro 2', 'airpods-pro-2', 6, 'Apple', 5990000, 5490000, 'airpods-pro-2.jpg', '["airpods-pro-2-1.jpg", "airpods-pro-2-2.jpg"]', 'AirPods Pro 2 - Chống ồn chủ động - Sạc MagSafe', 100, 1),
('Samsung Galaxy Buds2 Pro', 'samsung-buds2-pro', 6, 'Samsung', 3990000, 3490000, 'samsung-buds2-pro.jpg', '["samsung-buds2-pro-1.jpg"]', 'Galaxy Buds2 Pro - Chống ồn ANC - Bluetooth 5.3', 80, 0),
('Pin sạc dự phòng 20.000mAh', 'pin-sac-du-phong-20000mah', 7, 'Xiaomi', 990000, 890000, 'pin-20000mah.jpg', '["pin-20000mah-1.jpg"]', 'Pin sạc dự phòng Xiaomi 20.000mAh - 2 cổng USB', 150, 0),
('Ốp lưng iPhone 15 Pro Max', 'op-lung-iphone-15-pro-max', 8, 'Spigen', 490000, 390000, 'op-iphone-15.jpg', '["op-iphone-15-1.jpg"]', 'Ốp lưng Spigen cho iPhone 15 Pro Max - Chống sốc', 200, 0),
('Cáp sạc nhanh 100W', 'cap-sac-nhanh-100w', 9, 'Baseus', 290000, 249000, 'cap-sac-100w.jpg', '["cap-sac-100w-1.jpg"]', 'Cáp sạc nhanh Baseus 100W - Type C to C - 2m', 120, 0);

-- Cập nhật specifications (thông số kỹ thuật) cho sản phẩm
UPDATE products SET specifications = '{
    "screen": "6.7 inch Super Retina XDR",
    "chip": "A17 Pro",
    "ram": "8GB",
    "storage": "256GB",
    "camera_main": "48MP + 12MP + 12MP",
    "camera_selfie": "12MP",
    "battery": "4422mAh",
    "os": "iOS 17",
    "sim": "1 eSIM",
    "weight": "221g"
}' WHERE slug = 'iphone-15-pro-max-256gb';

UPDATE products SET specifications = '{
    "screen": "6.8 inch Dynamic AMOLED 2X",
    "chip": "Snapdragon 8 Gen 2",
    "ram": "12GB",
    "storage": "256GB",
    "camera_main": "200MP + 10MP + 10MP + 12MP",
    "camera_selfie": "12MP",
    "battery": "5000mAh",
    "os": "Android 13",
    "sim": "2 Nano SIM",
    "weight": "234g"
}' WHERE slug = 'samsung-s23-ultra-12gb-256gb';

-- 4. THÊM ĐƠN HÀNG MẪU
INSERT INTO orders (order_code, user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, discount_amount, shipping_fee, final_amount, payment_method, payment_status, order_status) VALUES
('ORD2024001', 2, 'Nguyễn Văn Minh', 'minh@email.com', '0912345678', '456 Lê Lợi, Quận 1, TP.HCM', 29990000, 1000000, 0, 28990000, 'cod', 'paid', 'delivered'),
('ORD2024002', 3, 'Trần Thị Thanh Thảo', 'thao@email.com', '0923456789', '789 Pasteur, Quận 3, TP.HCM', 17990000, 500000, 30000, 17790000, 'momo', 'paid', 'shipping'),
('ORD2024003', 4, 'Lê Hoàng Nam', 'nam@email.com', '0934567890', '321 Cách Mạng Tháng 8, Quận 10, TP.HCM', 7990000, 0, 30000, 8020000, 'banking', 'pending', 'confirmed'),
('ORD2024004', 2, 'Nguyễn Văn Minh', 'minh@email.com', '0912345678', '456 Lê Lợi, Quận 1, TP.HCM', 5490000, 0, 0, 5490000, 'cod', 'paid', 'delivered');

-- 5. THÊM CHI TIẾT ĐƠN HÀNG
INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, total_price) VALUES
(1, 1, 'iPhone 15 Pro Max 256GB', 29990000, 1, 29990000),
(2, 8, 'Xiaomi 13 Pro 12GB/256GB', 17990000, 1, 17990000),
(3, 6, 'Samsung Galaxy A54 5G 8GB/128GB', 7990000, 1, 7990000),
(4, 15, 'AirPods Pro 2', 5490000, 1, 5490000);

-- 6. THÊM VÀO GIỎ HÀNG
INSERT INTO carts (user_id, product_id, quantity) VALUES
(2, 1, 1),  -- Minh: iPhone 15 Pro Max
(2, 15, 2), -- Minh: AirPods Pro 2 x2
(3, 5, 1),  -- Thảo: Samsung S23 Ultra
(4, 9, 1);  -- Nam: Xiaomi 13 Pro

-- 7. THÊM YÊU THÍCH
INSERT INTO wishlists (user_id, product_id) VALUES
(2, 1),  -- Minh thích iPhone 15 Pro Max
(2, 5),  -- Minh thích Samsung S23 Ultra
(3, 8),  -- Thảo thích Xiaomi 13 Pro
(4, 1);  -- Nam thích iPhone 15 Pro Max

-- 8. THÊM ĐÁNH GIÁ SẢN PHẨM
INSERT INTO reviews (product_id, user_id, rating, title, comment, images, status) VALUES
(1, 2, 5, 'Sản phẩm tuyệt vời', 'iPhone 15 Pro Max quá đẹp, camera siêu nét, pin trâu. Rất hài lòng!', '["review-iphone1.jpg", "review-iphone2.jpg"]', 'approved'),
(1, 3, 4, 'Tốt nhưng giá hơi cao', 'Máy chạy mượt, thiết kế sang trọng. Chỉ có giá hơi cao so với túi tiền sinh viên.', NULL, 'approved'),
(5, 4, 5, 'Camera đỉnh cao', 'Camera 200MP chụp đẹp không tưởng. Bút S-Pen tiện lợi cho ghi chú.', '["review-samsung1.jpg"]', 'approved'),
(8, 2, 4, 'Xiaomi chất lượng', 'Xiaomi 13 Pro giá tốt, cấu hình mạnh. Camera Leica rất ấn tượng.', NULL, 'approved');

-- 9. THÊM TIN TỨC
INSERT INTO news (title, slug, content, excerpt, image, author, views, published_at) VALUES
('iPhone 16 sẽ có thay đổi gì?', 'iphone-16-se-co-thay-doi-gi', '<p>Apple dự kiến sẽ ra mắt iPhone 16 series vào tháng 9/2024 với nhiều cải tiến đáng chú ý...</p>', 'Dự đoán về iPhone 16 series sắp ra mắt', 'news-iphone16.jpg', 'Admin', 1250, '2024-03-01 10:00:00'),
('Top 5 điện thoại chơi game tốt nhất 2024', 'top-5-dien-thoai-choi-game-tot-nhat-2024', '<p>Nếu bạn là game thủ mobile, đây là 5 chiếc điện thoại bạn nên cân nhắc...</p>', 'Danh sách điện thoại chơi game mượt nhất', 'news-gaming.jpg', 'Editor', 890, '2024-03-15 14:30:00'),
('Cách bảo vệ pin điện thoại đúng cách', 'cach-bao-ve-pin-dien-thoai-dung-cach', '<p>Pin là bộ phận quan trọng của điện thoại. Dưới đây là mẹo giúp pin bền hơn...</p>', 'Hướng dẫn sử dụng và bảo quản pin điện thoại', 'news-battery.jpg', 'Expert', 1560, '2024-03-20 09:15:00');

-- 10. THÊM BANNER QUẢNG CÁO
INSERT INTO banners (title, image, link, position, sort_order, status, start_date, end_date) VALUES
('iPhone 15 Series - Giảm 10%', 'banner-iphone15.jpg', '/products?category=iphone', 'home_top', 1, 1, '2024-03-01', '2024-03-31'),
('Samsung S23 Ultra - Quà tặng hấp dẫn', 'banner-s23-ultra.jpg', '/product/samsung-s23-ultra-12gb-256gb', 'home_top', 2, 1, '2024-03-01', '2024-03-31'),
('Black Friday Sale - Giảm đến 50%', 'banner-blackfriday.jpg', '/promotions', 'home_middle', 1, 1, '2024-03-01', '2024-03-31'),
('Phụ kiện chính hãng - Giá sốc', 'banner-accessories.jpg', '/products?category=tai-nghe', 'home_bottom', 1, 1, '2024-03-01', '2024-03-31');

-- 11. THÊM MÃ GIẢM GIÁ
INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount_amount, usage_limit, start_date, end_date) VALUES
('WELCOME10', 'percent', 10, 0, 1000000, 100, '2024-01-01', '2024-12-31'),
('PHONE20', 'percent', 20, 10000000, 2000000, 50, '2024-01-01', '2024-06-30'),
('FREESHIP', 'fixed', 0, 500000, 50000, 200, '2024-01-01', '2024-12-31'),
('SALE50K', 'fixed', 50000, 3000000, 50000, 100, '2024-03-01', '2024-03-31');

-- 12. THÊM LIÊN HỆ
INSERT INTO contacts (name, email, phone, subject, message, status) VALUES
('Lê Văn Tùng', 'tung@email.com', '0945678901', 'Hỏi về bảo hành', 'Tôi mua iPhone 15 Pro Max được 1 tháng thì bị lỗi camera. Tôi có thể bảo hành ở đâu?', 'replied'),
('Phạm Thị Hương', 'huong@email.com', '0956789012', 'Tư vấn mua điện thoại', 'Tôi cần tư vấn mua điện thoại tầm 10 triệu cho sinh viên. Nên chọn máy nào?', 'read'),
('Trần Đức Anh', 'anh@email.com', '0967890123', 'Đăng ký đại lý', 'Tôi muốn trở thành đại lý phân phối điện thoại của PhoneStore. Xin tư vấn thủ tục.', 'pending');

-- 13. THÊM CẤU HÌNH HỆ THỐNG
INSERT INTO settings (`key`, `value`, `group`) VALUES
('site_name', 'PhoneStore', 'general'),
('site_email', 'contact@phonestore.vn', 'general'),
('site_phone', '1900 1234', 'general'),
('site_address', '123 Nguyễn Văn Linh, Quận 7, TP.HCM', 'general'),
('shipping_fee', '30000', 'shipping'),
('free_shipping_min', '5000000', 'shipping'),
('currency', 'VND', 'general'),
('logo', 'logo.png', 'appearance'),
('facebook_url', 'https://facebook.com/phonestore', 'social'),
('youtube_url', 'https://youtube.com/phonestore', 'social'),
('tiktok_url', 'https://tiktok.com/@phonestore', 'social');

-- ============================================
-- TẠO INDEX ĐỂ TĂNG HIỆU SUẤT
-- ============================================

CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_featured ON products(featured);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_carts_user ON carts(user_id);
CREATE INDEX idx_wishlists_user ON wishlists(user_id);
CREATE INDEX idx_reviews_product ON reviews(product_id);

-- ============================================
-- KIỂM TRA DỮ LIỆU
-- ============================================

SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM categories) as total_categories,
    (SELECT COUNT(*) FROM products) as total_products,
    (SELECT COUNT(*) FROM orders) as total_orders,
    (SELECT COUNT(*) FROM reviews) as total_reviews;