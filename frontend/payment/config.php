<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

/*
 * CẤU HÌNH VNPAY
 */
$config_path = __DIR__ . '/../../config.global.php';
if (file_exists($config_path)) {
    $config = require $config_path;
    $vnp_TmnCode = $config['vnpay']['tmn_code'];
    $vnp_HashSecret = $config['vnpay']['hash_secret'];
    $vnp_Url = $config['vnpay']['url'];
    $vnp_Returnurl = $config['vnpay']['return_url'];
    $vnp_apiUrl = $config['vnpay']['api_url'];
    $apiUrl = $config['vnpay']['api_transaction_url'];
} else {
    // Fallback to environment variables (e.g. on Vercel deployment)
    $vnp_TmnCode = getenv('VNP_TMN_CODE') ?: 'AUJ1BE5J';
    $vnp_HashSecret = getenv('VNP_HASH_SECRET') ?: 'P1PHAKP05ETBDMCA7B86FWGTNV3MDD98';
    $vnp_Url = getenv('VNP_URL') ?: 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    $vnp_Returnurl = getenv('VNP_RETURN_URL') ?: 'http://localhost/men_fashion_website/frontend/payment/vnpay_return.php';
    $vnp_apiUrl = getenv('VNP_API_URL') ?: 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html';
    $apiUrl = getenv('VNP_API_TRANSACTION_URL') ?: 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';
}
?>