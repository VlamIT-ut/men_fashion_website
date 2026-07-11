<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include("config.php"); // Kết nối DB

$result = $conn->query("SHOW TABLES");
$tables = [];
if ($result) {
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    $result->free();
}

$conn->close();

echo json_encode([
    "message" => "Connected successfully!",
    "tables" => $tables
]);
?>
