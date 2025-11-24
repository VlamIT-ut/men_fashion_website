<?php
// db.php - kết nối MySQL với PDO đơn giản
$host = 'shinkansen.proxy.rlwy.net';
$db   = 'railway';
$user = 'root';
$pass = 'uEVFXfGAonZwMAEnrrDTZYcKkSJcaGbm';
$port = '44698';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Kết nối DB thành công!";
} catch (PDOException $e) {
    die("Kết nối DB thất bại: " . $e->getMessage());
}
