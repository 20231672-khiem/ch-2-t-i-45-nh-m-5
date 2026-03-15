<?php
// includes/functions.php

// Làm sạch dữ liệu nhập vào
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Định dạng tiền Việt Nam
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

// Chuyển hướng trang
function redirect($url) {
    header("Location: $url");
    exit();
}
?>