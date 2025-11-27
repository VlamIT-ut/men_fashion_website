<?php
// Nếu folder là frontend/payment/config.php thì giữ như này
require_once("./config.php");

/*
 * LẤY DỮ LIỆU TỪ checkout.php (GỬI BẰNG GET)
 *  - order_id : mã đơn hàng trong bảng don_hang
 *  - amount   : tổng tiền (VND, chưa *100)
 *  - bank_code: mã ngân hàng (NCB, VNPAYQR, ...)
 */
$orderId  = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$amount   = isset($_GET['amount'])   ? (int)$_GET['amount']   : 0;
$bankCode = isset($_GET['bank_code']) ? $_GET['bank_code']   : "";

// Nếu muốn test độc lập bằng form POST cũ thì có thể ưu tiên POST:
if ($orderId == 0 && isset($_POST['order_id'])) {
    $orderId = (int)$_POST['order_id'];
}
if ($amount == 0 && isset($_POST['amount'])) {
    $amount = (int)$_POST['amount'];
}
if ($bankCode == "" && isset($_POST['bank_code'])) {
    $bankCode = $_POST['bank_code'];
}

// Kiểm tra sơ bộ:
if ($amount <= 0) {
    die("Số tiền thanh toán không hợp lệ (amount <= 0).");
}

// MÃ ĐƠN HÀNG GỬI LÊN VNPAY
// Thường dùng luôn ID đơn hàng để dễ đối chiếu
$vnp_TxnRef    = $orderId > 0 ? $orderId : time();
$vnp_OrderInfo = "Thanh toan don hang #" . $vnp_TxnRef;
$vnp_OrderType = "billpayment";

// QUAN TRỌNG: vnp_Amount = số tiền * 100, phải là số nguyên, không có dấu chấm phẩy
$vnp_Amount    = $amount * 100;

$vnp_Locale    = "vn";
$vnp_IpAddr    = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version"   => "2.1.0",
    "vnp_TmnCode"   => $vnp_TmnCode,
    "vnp_Amount"    => $vnp_Amount,
    "vnp_Command"   => "pay",
    "vnp_CreateDate"=> date('YmdHis'),
    "vnp_CurrCode"  => "VND",
    "vnp_IpAddr"    => $vnp_IpAddr,
    "vnp_Locale"    => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef"    => $vnp_TxnRef
);

// Thêm ngân hàng nếu có
if (!empty($bankCode)) {
    $inputData['vnp_BankCode'] = $bankCode;
}

// Sắp xếp tham số A-Z đúng yêu cầu VNPAY
ksort($inputData);

$query    = "";
$hashdata = "";
$i = 0;
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

// Tạo URL
$vnp_Url = $vnp_Url . "?" . $query;

// Ký HMAC SHA512
if (isset($vnp_HashSecret) && $vnp_HashSecret != "") {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= "vnp_SecureHash=" . $vnpSecureHash;
}

// Debug tạm (nếu muốn xem URL trước khi redirect)
// echo $vnp_Url; exit;

// Chuyển hướng sang VNPAY
header('Location: ' . $vnp_Url);
exit;
