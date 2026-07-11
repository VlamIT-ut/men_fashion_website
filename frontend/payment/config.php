<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

/*
 * CẤU HÌNH VNPAY
 */
$config_path = __DIR__ . '/../../config.global.php';
if (!file_exists($config_path)) {
    die("Thiếu file cấu hình hệ thống config.global.php");
}
$config = require $config_path;

$vnp_TmnCode = $config['vnpay']['tmn_code'];
$vnp_HashSecret = $config['vnpay']['hash_secret'];
$vnp_Url = $config['vnpay']['url'];
$vnp_Returnurl = $config['vnpay']['return_url'];
$vnp_apiUrl = $config['vnpay']['api_url'];
$apiUrl = $config['vnpay']['api_transaction_url'];
?>