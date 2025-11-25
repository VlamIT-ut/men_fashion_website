<?php
session_start();

// **CHÚ Ý ĐƯỜNG DẪN db.php**
// File này nằm trong /frontend/admin/ nên phải lùi 1 cấp
include "../db.php";

// Nếu chưa đăng nhập admin -> đẩy về trang login
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit;
}

// Lấy tên admin để hiển thị
$adminName = is_array($_SESSION['admin'])
    ? ($_SESSION['admin']['username'] ?? 'Admin')
    : $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang quản trị - Cửa hàng Nam</title>
    <!-- Dùng CDN Bootstrap cho nhanh -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background:#f5f5f5; }
        .sidebar {
            height: 100vh;
            background:#0d6efd;
            color:#fff;
            padding-top:20px;
        }
        .sidebar a {
            color:#fff;
            text-decoration:none;
            display:block;
            padding:10px 15px;
            border-radius:8px;
            margin-bottom:5px;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background:rgba(255,255,255,0.2);
        }
        .admin-topbar {
            background:#fff;
            padding:10px 20px;
            border-bottom:1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <nav class="col-md-2 sidebar">
            <div class="px-3 mb-4">
                <h5 class="mb-0">Admin Panel</h5>
                <small><?php echo htmlspecialchars($adminName); ?></small>
            </div>

            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='index.php'?'active':''; ?>">
                🏠 Dashboard
            </a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='products.php'?'active':''; ?>">
                👕 Quản lý sản phẩm
            </a>
            <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='users.php'?'active':''; ?>">
                👤 Quản lý người dùng
            </a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='orders.php'?'active':''; ?>">
                🧾 Quản lý đơn hàng
            </a>
            <a href="logout_admin.php">
                🚪 Đăng xuất
            </a>
        </nav>

        <!-- PHẦN NỘI DUNG -->
        <main class="col-md-10 p-0">
            <div class="admin-topbar d-flex justify-content-between align-items-center">
                <span><strong>Bảng điều khiển quản trị</strong></span>
                <span>Xin chào, <?php echo htmlspecialchars($adminName); ?></span>
            </div>

            <div class="p-4">
                <!-- Các trang con sẽ đặt nội dung vào đây (sau khi include admin_header.php) -->
