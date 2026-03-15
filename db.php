<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "phonestore_db"; // Tên database của bạn

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>