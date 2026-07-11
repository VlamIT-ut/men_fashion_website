<?php
$config_path = __DIR__ . '/../config.global.php';
if (!file_exists($config_path)) {
    die(json_encode(["error" => "Thiếu file cấu hình hệ thống config.global.php"]));
}
$config = require $config_path;

$hostname = $config['db']['host'];
$username = $config['db']['user'];
$password = $config['db']['pass'];
$dbname   = $config['db']['name'];
$port     = $config['db']['port'];

$conn = new mysqli($hostname, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}
?>
