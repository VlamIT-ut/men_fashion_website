<?php
header("Access-Control-Allow-Origin: frontend/index.html"); // Cho phép FE gọi
header("Content-Type: application/json");

include("config.php"); // Kết nối DB

$result = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

echo json_encode([
    "message" => "Connected successfully!",
    "tables" => $tables
]);
?>
