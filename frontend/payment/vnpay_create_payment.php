<?php
require_once("./config.php");

// --- ĐOẠN MỚI: LẤY DỮ LIỆU TỪ FORM ---
$vnp_TxnRef = time(); // Mã đơn hàng sinh tự động
$vnp_OrderInfo = $_POST['order_desc']; // Lấy từ form
$vnp_OrderType = 'billpayment';
// Quan trọng: Lấy số tiền từ form và NHÂN 100
$vnp_Amount = $_POST['amount'] * 100; 
$vnp_Locale = 'vn';
$vnp_BankCode = $_POST['bank_code']; // Lấy từ form
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);

if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

// Sắp xếp mảng tham số theo A-Z (Bắt buộc của VNPAY)
ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); // VNPAY dùng SHA512
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

// Chuyển hướng sang VNPAY
header('Location: ' . $vnp_Url);
die();
?>