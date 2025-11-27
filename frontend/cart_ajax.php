<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

header('Content-Type: application/json; charset=utf-8');

$id  = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($id <= 0 || $qty <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

// Lấy sản phẩm từ CSDL
$sql = "SELECT ma_sp, ten_sp, gia 
        FROM san_pham 
        WHERE ma_sp = $id AND ton_tai = b'1'
        LIMIT 1";
$rs  = mysqli_query($conn, $sql);
if (!$rs || !($p = mysqli_fetch_assoc($rs))) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
    exit;
}

// Thêm vào session giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id'    => $id,
        'name'  => $p['ten_sp'],
        'price' => $p['gia'],
        'qty'   => $qty
    ];
}

// Đếm tổng số lượng trong giỏ
$totalQty = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalQty += (int)$item['qty'];
}

// Render lại mini-cart ra HTML
ob_start();
include 'mini_cart.php';   // file này bạn đã có sẵn
$miniCartHtml = ob_get_clean();

echo json_encode([
    'status'         => 'success',
    'message'        => 'Đã thêm vào giỏ hàng',
    'cart_count'     => $totalQty,
    'mini_cart_html' => $miniCartHtml
]);
