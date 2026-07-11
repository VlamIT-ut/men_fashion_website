<?php
$config_path = __DIR__ . '/../config.global.php';
if (file_exists($config_path)) {
    $config = require $config_path;
    $hostname = $config['db']['host'];
    $username = $config['db']['user'];
    $password = $config['db']['pass'];
    $dbname   = $config['db']['name'];
    $port     = $config['db']['port'];
} else {
    // Fallback to environment variables (e.g. on Vercel deployment)
    $hostname = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
    $dbname   = getenv('DB_NAME') ?: 'men_fashion';
    $port     = getenv('DB_PORT') ?: 3306;
}

$conn = new mysqli($hostname, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8mb4");
?>
