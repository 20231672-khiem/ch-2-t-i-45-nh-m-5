<?php
// admin/get_chart_data.php - Lấy dữ liệu biểu đồ
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Doanh thu 30 ngày gần nhất
$labels = [];
$data = [];

for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d/m', strtotime($date));
    
    $revenue = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COALESCE(SUM(final_amount), 0) as total 
         FROM orders 
         WHERE DATE(created_at) = '$date' 
         AND order_status = 'delivered'"
    ))['total'];
    $data[] = $revenue;
}

// Doanh thu theo tháng
$monthly_labels = [];
$monthly_data = [];

for ($i = 5; $i >= 0; $i--) {
    $month = date('m/Y', strtotime("-$i months"));
    $labels[] = $month;
    
    $start = date('Y-m-01', strtotime("-$i months"));
    $end = date('Y-m-t', strtotime("-$i months"));
    
    $revenue = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COALESCE(SUM(final_amount), 0) as total 
         FROM orders 
         WHERE DATE(created_at) BETWEEN '$start' AND '$end'
         AND order_status = 'delivered'"
    ))['total'];
    $monthly_data[] = $revenue;
}

echo json_encode([
    'daily' => [
        'labels' => $labels,
        'data' => $data
    ],
    'monthly' => [
        'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
        'data' => $monthly_data
    ]
]);
?>