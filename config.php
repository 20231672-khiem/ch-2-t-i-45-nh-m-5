<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Thông tin database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'phonestore_db');


// Kết nối database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// Kiểm tra đăng nhập
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Kiểm tra admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

?>