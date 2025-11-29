<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Nhận dữ liệu từ AJAX
$id  = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($id <= 0 || $qty <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

require 'db.php'; // Kết nối DB

// Lấy thông tin sản phẩm
$sql = "
    SELECT sp.ma_sp, sp.ten_sp, sp.gia,
           (SELECT ha.ten_anh 
            FROM hinhanh_sp ha 
            WHERE ha.ma_sp = sp.ma_sp LIMIT 1) AS hinh
    FROM san_pham sp
    WHERE sp.ma_sp = $id
    LIMIT 1
";

$rs = mysqli_query($conn, $sql);
if (!$rs || mysqli_num_rows($rs) == 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
    exit;
}

$sp = mysqli_fetch_assoc($rs);

// Khởi tạo giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm hoặc tăng số lượng
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id'     => $sp['ma_sp'],
        'ten_sp' => $sp['ten_sp'],
        'gia'    => (int)$sp['gia'],
        'qty'    => $qty,
        'hinh'   => $sp['hinh'] ?: 'no-image.jpg'
    ];
}

// ------- TÍNH LẠI SỐ LƯỢNG -------
$cartTotalQty = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartTotalQty += (int)$item['qty'];
}

// ------- RENDER MINI CART -------
ob_start();
include 'mini_cart.php';
$miniCartHTML = ob_get_clean();

// Trả về JSON cho JS
echo json_encode([
    'status'         => 'success',
    'cart_count'     => $cartTotalQty,
    'mini_cart_html' => $miniCartHTML
]);
exit;