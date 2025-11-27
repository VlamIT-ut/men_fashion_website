<?php
require_once("./config.php");

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// --- XỬ LÝ TRẠNG THÁI GIAO DỊCH ---
$status = "";
$message = "";
$icon = "";
$cssClass = "";

if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        // THÀNH CÔNG
        $status = "Giao dịch thành công!";
        $message = "Cảm ơn bạn đã thanh toán. Đơn hàng của bạn đang được xử lý.";
        $icon = "✅";
        $cssClass = "success";
        // TODO: Cập nhật database tại đây (nếu chưa dùng IPN)
    } else {
        // THẤT BẠI (Do hủy, không đủ tiền, lỗi bank...)
        $status = "Giao dịch thất bại";
        $message = "Đã có lỗi xảy ra hoặc bạn đã hủy giao dịch.";
        $icon = "❌";
        $cssClass = "error";
    }
} else {
    // SAI CHỮ KÝ
    $status = "Cảnh báo bảo mật";
    $message = "Chữ ký không hợp lệ! Vui lòng kiểm tra lại.";
    $icon = "⚠️";
    $cssClass = "warning";
}

// Format lại tiền tệ và ngày tháng cho đẹp
$amount = number_format($_GET['vnp_Amount'] / 100) . " VNĐ";
$dateRaw = $_GET['vnp_PayDate'];
$dateFormatted = date_format(date_create_from_format('YmdHis', $dateRaw), 'H:i:s d/m/Y');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center; }
        
        /* Trạng thái màu sắc */
        .icon { font-size: 60px; margin-bottom: 20px; }
        .success .icon { color: #28a745; }
        .success h2 { color: #28a745; }
        
        .error .icon { color: #dc3545; }
        .error h2 { color: #dc3545; }
        
        .warning .icon { color: #ffc107; }
        .warning h2 { color: #ffc107; }

        p.message { color: #666; font-size: 16px; margin-bottom: 30px; }

        /* Bảng thông tin chi tiết */
        .info-table { width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 30px; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid #eee; }
        .info-table td:last-child { text-align: right; font-weight: bold; color: #333; }
        .info-table tr:last-child td { border-bottom: none; }

        /* Nút bấm */
        .btn { display: inline-block; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-home { background-color: #007bff; color: white; }
        .btn-home:hover { background-color: #0056b3; }
        
        .footer { margin-top: 20px; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>

<div class="card <?php echo $cssClass; ?>">
    <div class="icon"><?php echo $icon; ?></div>
    <h2><?php echo $status; ?></h2>
    <p class="message"><?php echo $message; ?></p>

    <?php if ($secureHash == $vnp_SecureHash): ?>
        <table class="info-table">
            <tr>
                <td>Mã đơn hàng:</td>
                <td>#<?php echo $_GET['vnp_TxnRef']; ?></td>
            </tr>
            <tr>
                <td>Số tiền:</td>
                <td style="color: #d32f2f;"><?php echo $amount; ?></td>
            </tr>
            <tr>
                <td>Ngân hàng:</td>
                <td><?php echo $_GET['vnp_BankCode']; ?></td>
            </tr>
            <tr>
                <td>Mã giao dịch VNPAY:</td>
                <td><?php echo $_GET['vnp_TransactionNo']; ?></td>
            </tr>
            <tr>
                <td>Thời gian:</td>
                <td><?php echo $dateFormatted; ?></td>
            </tr>
            <tr>
                <td>Nội dung:</td>
                <td><?php echo $_GET['vnp_OrderInfo']; ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <a href="http://localhost/men_fashion_website/frontend/product.php" class="btn btn-home">Quay lại trang chủ</a>

    <div class="footer">
        Hệ thống thanh toán VNPAY Sandbox
    </div>
</div>

</body>
</html>