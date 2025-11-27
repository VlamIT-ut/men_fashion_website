<?php
session_start();
include 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: product.php");
    exit;
}

// Lấy sản phẩm
$sql = "SELECT sp.ma_sp, sp.ten_sp, sp.gia,
        (SELECT ten_anh FROM hinhanh_sp ha WHERE ha.ma_sp = sp.ma_sp LIMIT 1) AS hinh
        FROM san_pham sp
        WHERE sp.ma_sp = $id";
$res = mysqli_query($conn, $sql);
$p = mysqli_fetch_assoc($res);
if (!$p) {
    header("Location: product.php");
    exit;
}

// Tạo giỏ nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nếu đã có -> tăng số lượng
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty']++;
} else {
    $_SESSION['cart'][$id] = [
        'id'    => $p['ma_sp'],
        'name'  => $p['ten_sp'],
        'price' => (float)$p['gia'],
        'qty'   => 1,
        'image' => $p['hinh'] ?: 'no-image.jpg',
    ];
}

header("Location: shoping-cart.php");
exit;
