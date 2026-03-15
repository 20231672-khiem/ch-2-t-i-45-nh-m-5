-- TẠO DATABASE ĐƠN GIẢN CHO TEST
CREATE DATABASE IF NOT EXISTS phone_store_simple;
USE phone_store_simple;

-- Bảng users đơn giản
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Bảng products đơn giản
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    price DECIMAL(10,2),
    image VARCHAR(255),
    category VARCHAR(50)
);

-- Thêm dữ liệu test (mật khẩu: 123456)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user1', 'user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO products (name, price, image, category) VALUES
('iPhone 15 Pro Max', 29990000, 'iphone.jpg', 'iphone'),
('Samsung S23 Ultra', 25990000, 'samsung.jpg', 'samsung'),
('Xiaomi 13 Pro', 17990000, 'xiaomi.jpg', 'xiaomi'),
('AirPods Pro 2', 5490000, 'airpods.jpg', 'accessory');