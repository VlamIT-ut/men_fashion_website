<?php
$hostname = "sql201.infinityfree.com";
$username = "if0_40371088";
$password = "Pgjan0201";
$dbname = "if0_40371088_dbeshop";

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}
?>
