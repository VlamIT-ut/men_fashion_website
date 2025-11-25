<?php
session_start();
include 'db.php';

// Lấy dữ liệu từ AJAX (POST)
$id  = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($id <= 0 || $qty <= 0) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

// Lấy thông tin sản phẩm
$sql = "
    SELECT 
        sp.ma_sp, sp.ten_sp, sp.gia,
        (SELECT ha.ten_anh FROM hinhanh_sp ha 
         WHERE ha.ma_sp = sp.ma_sp LIMIT 1) AS hinh
    FROM san_pham sp
    WHERE sp.ma_sp = $id
    LIMIT 1
";
$rs = mysqli_query($conn, $sql);

if (!$rs || mysqli_num_rows($rs) == 0) {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
    exit;
}

$sp = mysqli_fetch_assoc($rs);

// Khởi tạo giỏ
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm / tăng số lượng
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id'     => $sp['ma_sp'],
        'ten_sp' => $sp['ten_sp'],
        'gia'    => (float)$sp['gia'],
        'qty'    => $qty,
        'hinh'   => $sp['hinh'] ?? 'no-image.jpg'
    ];
}

// Tính tổng số lượng + tổng tiền để trả về cho JS
$totalItems = 0;              // tổng số lượng (qty)
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $q = (int)($item['qty'] ?? 1);
    $p = (float)($item['gia'] ?? 0);
    $totalItems += $q;
    $totalPrice += $q * $p;
}

// Số item để hiển thị badge (tuỳ bạn, ở đây lấy tổng số lượng)
$cartCount = $totalItems;

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'status'             => 'success',
    'cart_count'         => $cartCount,                         // dùng cho badge trên icon
    'total_items'        => $totalItems,
    'total_price'        => $totalPrice,
    'total_price_format' => number_format($totalPrice, 0, ',', '.') . '₫'
]);
exit;
