<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$id     = isset($_POST['id'])     ? (int)$_POST['id']     : 0;
$action = isset($_POST['action']) ? $_POST['action']      : '';

if ($id <= 0 || $action === '') {
    echo json_encode(['success' => false, 'msg' => 'Dữ liệu không hợp lệ']);
    exit;
}

if (!isset($_SESSION['cart'][$id])) {
    // Không có trong giỏ thì thôi
    echo json_encode(['success' => true, 'cart_total' => '0₫', 'cart_count' => 0, 'item_qty' => 0]);
    exit;
}

// Xử lý theo action
switch ($action) {
    case 'inc':
        $_SESSION['cart'][$id]['qty']++;
        break;

    case 'dec':
        $_SESSION['cart'][$id]['qty']--;
        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$id]);
        break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Action không hợp lệ']);
        exit;
}

// Tính lại tổng giỏ
$cartTotal = 0;
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $q = (int)($item['qty'] ?? 1);
    $p = (float)($item['gia'] ?? 0);
    $cartCount += $q;
    $cartTotal += $q * $p;
}

$itemQty = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id]['qty'] : 0;

echo json_encode([
    'success'    => true,
    'item_qty'   => $itemQty,
    'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫',
    'cart_count' => $cartCount
]);
exit;
