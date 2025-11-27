<?php
session_start();

// luôn dùng đường dẫn tuyệt đối theo thư mục file hiện tại cho chắc
include __DIR__ . '/../db.php';
        // hoặc include 'admin_header.php' nếu trong đó có sẵn $conn + check admin

// NÊN có kiểm tra quyền admin ở đây
// if (empty($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: users.php");
    exit;
}

$id     = (int)$_GET['id'];
$action = $_GET['action'] === 'lock' ? 'lock' : 'unlock';

$newStatus = ($action === 'lock') ? 0 : 1;

$sql  = "UPDATE khach_hang SET trang_thai = ? WHERE ma_kh = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $newStatus, $id);
mysqli_stmt_execute($stmt);

// Sau khi xử lý xong quay lại danh sách
header("Location: users.php");
exit;
