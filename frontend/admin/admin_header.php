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
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5.3.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc; 
            color: #1e293b;
        }
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            color: #94a3b8;
            padding: 24px 16px;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 0 12px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 24px;
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .sidebar-brand small {
            color: #38bdf8;
            font-weight: 500;
        }
        .sidebar a {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
        }
        .sidebar a i {
            font-size: 18px;
            transition: transform 0.2s;
        }
        .sidebar a:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            transform: translateX(4px);
        }
        .sidebar a:hover i {
            transform: scale(1.1);
        }
        .sidebar a.active {
            color: #fff;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }
        .admin-topbar {
            background: #fff;
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .admin-topbar h6 {
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }
        .admin-user-badge {
            background: #f1f5f9;
            padding: 6px 16px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
        }
        .admin-user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <nav class="col-md-2 sidebar d-none d-md-block">
            <div class="sidebar-brand">
                <h5 class="mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-grid-1x2-fill text-primary"></i> Admin Panel
                </h5>
                <small class="d-flex align-items-center gap-1.5">
                    <i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i> <?php echo htmlspecialchars($adminName); ?>
                </small>
            </div>

            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='index.php'?'active':''; ?>">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='products.php'?'active':''; ?>">
                <i class="bi bi-box-seam"></i> Quản lý sản phẩm
            </a>
            <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='users.php'?'active':''; ?>">
                <i class="bi bi-people"></i> Quản lý người dùng
            </a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='orders.php'?'active':''; ?>">
                <i class="bi bi-receipt"></i> Quản lý đơn hàng
            </a>
            <a href="logout_admin.php" class="mt-5 text-danger">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
        </nav>

        <!-- PHẦN NỘI DUNG -->
        <main class="col-md-10 p-0">
            <div class="admin-topbar d-flex justify-content-between align-items-center">
                <h6>Bảng điều khiển quản trị</h6>
                <div class="admin-user-badge">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($adminName, 0, 1)); ?></div>
                    <span>Xin chào, <strong><?php echo htmlspecialchars($adminName); ?></strong></span>
                </div>
            </div>

            <div class="p-4" style="min-height: calc(100vh - 60px);">
                <!-- Các trang con sẽ đặt nội dung vào đây (sau khi include admin_header.php) -->
