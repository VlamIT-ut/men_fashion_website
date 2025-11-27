<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

/*
 * CẤU HÌNH VNPAY
 */
$vnp_TmnCode = "AUJ1BE5J"; // Website ID (Thay bằng mã của bạn)
$vnp_HashSecret = "P1PHAKP05ETBDMCA7B86FWGTNV3MDD98"; // Chuỗi bí mật (Thay bằng mã của bạn)
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/men_fashion_website/frontend/payment/vnpay_return.php"; // URL nhận kết quả trả về
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
?>