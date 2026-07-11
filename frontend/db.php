<?php
$config_path = __DIR__ . '/../config.global.php';
if (!file_exists($config_path)) {
    die("Thiếu file cấu hình hệ thống config.global.php. Vui lòng copy config.global.example.php thành config.global.php.");
}
$config = require $config_path;

$hostname = $config['db']['host'];
$username = $config['db']['user'];
$password = $config['db']['pass'];
$dbname   = $config['db']['name'];
$port     = $config['db']['port'];

$conn = new mysqli($hostname, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8mb4");
?>
