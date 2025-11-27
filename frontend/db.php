<?php
$hostname = "shinkansen.proxy.rlwy.net";
$username = "root";
$password = "uEVFXfGAonZwMAEnrrDTZYcKkSJcaGbm";
$dbname   = "railway";
$port     = 44698; // MYSQLPORT

$conn = new mysqli($hostname, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8mb4");
?>
