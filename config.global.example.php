<?php
// Template for config.global.php. Duplicate this file as config.global.php and update values.
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit('Direct access not allowed');
}

return [
    'db' => [
        'host' => 'localhost',
        'user' => 'your_db_username',
        'pass' => 'your_db_password',
        'name' => 'your_db_name',
        'port' => 3306
    ],
    'vnpay' => [
        'tmn_code' => 'your_vnp_tmn_code',
        'hash_secret' => 'your_vnp_hash_secret',
        'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'return_url' => 'http://localhost/men_fashion_website/frontend/payment/vnpay_return.php',
        'api_url' => 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html',
        'api_transaction_url' => 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'
    ]
];
