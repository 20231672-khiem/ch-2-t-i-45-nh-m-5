<?php
// admin/mark_notification.php - Đánh dấu đã đọc thông báo
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

if (isset($_GET['id'])) {
    // Đánh dấu 1 thông báo
    $id = intval($_GET['id']);
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE id = $id AND user_id = {$_SESSION['user_id']}");
} else {
    // Đánh dấu tất cả
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = {$_SESSION['user_id']}");
}

// Redirect back
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'dashboard.php'));
exit();
?>