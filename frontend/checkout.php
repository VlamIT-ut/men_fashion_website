<?php
session_start();
include 'db.php'; // Kết nối DB
require_once 'payment/config.php'; // <--- GỌI FILE CẤU HÌNH VNPAY TẠI ĐÂY

// 1. Kiểm tra giỏ hàng
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: shoping-cart.php');
    exit;
}

// 2. Tính tổng tiền
$subtotal = 0;
foreach ($cart as $item) {
    $qty = (int)($item['qty'] ?? 1);
    $subtotal += $item['gia'] * $qty;
}
$shippingFee = 30000; 
$totalPrice = $subtotal + $shippingFee;

// 3. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT "ĐẶT HÀNG"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    
    // Lấy thông tin khách hàng
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = $_POST['note'];
    $payment_method = $_POST['payment_method'];

    // TODO: LƯU ĐƠN HÀNG VÀO DATABASE (Status: Pending)
    $orderId = time(); // Tạm dùng timestamp làm mã đơn

    // --- LOGIC THANH TOÁN ---

    if ($payment_method === 'vnpay') {
        
        // === TÍCH HỢP VNPAY (Đã dùng biến từ config) ===
        
        $vnp_TxnRef = $orderId; 
        $vnp_OrderInfo = "Thanh toan don hang #" . $orderId;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $totalPrice * 100; // Nhân 100 theo quy định
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB"; // Ngân hàng demo
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode, // Biến này lấy từ vnpay_config.php
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl, // Biến này lấy từ vnpay_config.php
            "vnp_TxnRef" => $vnp_TxnRef
        );

        // Sắp xếp dữ liệu
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

        // Tạo URL thanh toán
        $vnp_Url = $vnp_Url . "?" . $query; // $vnp_Url gốc lấy từ config
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Chuyển hướng
        header('Location: ' . $vnp_Url);
        exit;

    } else {
        // === THANH TOÁN COD ===
        echo "<script>alert('Đặt hàng thành công (COD)!'); window.location.href='index.php';</script>";
        exit;
    }
}

// Điền sẵn thông tin user (nếu có)
$currentUser = $_SESSION['user'] ?? null;
$fillName = $currentUser['HoTen'] ?? $currentUser['username'] ?? "";
$fillPhone = $currentUser['DienThoai'] ?? "";
$fillAddress = $currentUser['DiaChi'] ?? "";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thanh toán</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .checkout-box { background: #fff; padding: 30px; border: 1px solid #e6e6e6; border-radius: 3px; }
        .checkout-title { font-family: Poppins-Bold; font-size: 20px; color: #333; text-transform: uppercase; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .form-group label { font-family: Poppins-Medium; font-size: 14px; color: #333; margin-bottom: 8px; }
        .stext-111 { height: 45px; }
        .payment-option { margin-bottom: 15px; padding: 15px; border: 1px solid #e6e6e6; border-radius: 5px; cursor: pointer; transition: all 0.3s; }
        .payment-option:hover { border-color: #717fe0; }
        .payment-option input { margin-right: 10px; }
        .order-summary-item { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; color: #666; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
        .order-total { display: flex; justify-content: space-between; margin-top: 20px; font-family: Poppins-Bold; font-size: 18px; color: #333; border-top: 2px solid #333; padding-top: 15px; }
    </style>
</head>
<body class="animsition">

    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">Trang chủ <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i></a>
            <a href="shoping-cart.php" class="stext-109 cl8 hov-cl1 trans-04">Giỏ hàng <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i></a>
            <span class="stext-109 cl4">Thanh toán</span>
        </div>
    </div>

    <form method="post" action="checkout.php" class="bg0 p-t-40 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 m-b-50">
                    <div class="checkout-box">
                        <h4 class="checkout-title">Thông tin giao hàng</h4>
                        <div class="row">
                            <div class="col-md-6 p-b-20">
                                <div class="form-group">
                                    <label>Họ và tên *</label>
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18 bor8" type="text" name="fullname" value="<?php echo htmlspecialchars($fillName); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 p-b-20">
                                <div class="form-group">
                                    <label>Số điện thoại *</label>
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18 bor8" type="text" name="phone" value="<?php echo htmlspecialchars($fillPhone); ?>" required>
                                </div>
                            </div>
                            <div class="col-12 p-b-20">
                                <div class="form-group">
                                    <label>Địa chỉ nhận hàng *</label>
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18 bor8" type="text" name="address" value="<?php echo htmlspecialchars($fillAddress); ?>" required>
                                </div>
                            </div>
                            <div class="col-12 p-b-20">
                                <div class="form-group">
                                    <label>Ghi chú đơn hàng</label>
                                    <textarea class="stext-111 cl2 plh3 size-120 p-lr-18 bor8 p-t-15" style="height: 100px;" name="note" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 m-b-50">
                    <div class="checkout-box bg-light" style="background-color: #f9f9f9;">
                        <h4 class="checkout-title">Đơn hàng của bạn</h4>
                        <div class="order-list m-b-30">
                            <?php foreach ($cart as $item): ?>
                            <div class="order-summary-item">
                                <div>
                                    <span class="header-cart-item-info">
                                        <?php echo $item['qty']; ?> x <?php echo htmlspecialchars($item['ten_sp']); ?>
                                    </span>
                                </div>
                                <div><?php echo number_format($item['gia'] * $item['qty'], 0, ',', '.'); ?>₫</div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex-w flex-t p-t-10 p-b-10 bor12">
                            <div class="size-208"><span class="stext-110 cl2">Tạm tính:</span></div>
                            <div class="size-209"><span class="mtext-110 cl2" style="font-size: 16px;"><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</span></div>
                        </div>
                        <div class="flex-w flex-t p-t-10 p-b-10 bor12">
                            <div class="size-208"><span class="stext-110 cl2">Phí ship:</span></div>
                            <div class="size-209"><span class="mtext-110 cl2" style="font-size: 16px;"><?php echo number_format($shippingFee, 0, ',', '.'); ?>₫</span></div>
                        </div>
                        <div class="order-total">
                            <span>Tổng cộng:</span>
                            <span style="color: #d32f2f;"><?php echo number_format($totalPrice, 0, ',', '.'); ?>₫</span>
                        </div>

                        <div class="p-t-30">
                            <h4 class="checkout-title" style="font-size: 16px; margin-bottom: 15px;">Phương thức thanh toán</h4>
                            
                            <label class="payment-option d-block">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <span class="stext-111 text-dark">Thanh toán khi nhận hàng (COD)</span>
                            </label>

                            <label class="payment-option d-block">
                                <input type="radio" name="payment_method" value="vnpay">
                                <span class="stext-111 text-primary" style="font-weight: bold;">
                                    Thanh toán VNPAY QR
                                    <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg" alt="VNPAY" style="height: 20px; margin-left: 10px;">
                                </span>
                            </label>
                        </div>

                        <button type="submit" name="place_order" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer m-t-30">
                            ĐẶT HÀNG NGAY
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>