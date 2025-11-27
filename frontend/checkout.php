<?php
session_start();
include "db.php";

// Bắt buộc phải login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user  = $_SESSION['user'];
$ma_kh = (int)$user['id'];

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die("Giỏ hàng của bạn đang trống.");
}

// Tính tạm tính
$tam_tinh = 0;
foreach ($cart as $item) {
    $gia = (int)$item['gia'];
    $qty = (int)($item['qty'] ?? 1);
    $tam_tinh += $gia * $qty;
}

$phi_ship  = 0;
$tong_tien = $tam_tinh + $phi_ship;
$errors    = "";

// Khi bấm Đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_kh    = trim($_POST['ten_kh']);
    $dia_chi   = trim($_POST['dia_chi']);
    $sdt       = trim($_POST['sdt']);
    $payMethod = $_POST['payment_method'] ?? 'cod';   // cod / vnpay

    if ($ten_kh === "" || $dia_chi === "" || $sdt === "") {
        $errors = "Vui lòng nhập đầy đủ thông tin giao hàng.";
    } else {
        // ma_pt: 1 = COD, 2 = VNPAY
        $ma_pt         = ($payMethod === 'vnpay') ? 2 : 1;
        $tt_thanhtoan  = 0;      // VNPAY thành công mới cập nhật sau
        $trangthai_don = 0;      // 0: chờ xác nhận

        mysqli_begin_transaction($conn);

        try {
            // ========== CHÈN ĐƠN HÀNG ==========
            $sqlOrder = "INSERT INTO don_hang
                (ma_kh, ma_pt, tam_tinh, phi_ship, tong_tien,
                 ngay_tao, ten_kh, dia_chi, sdt,
                 trangthai_thanhtoan, trangthai_don)
             VALUES
                (?, ?, ?, ?, ?,
                 NOW(), ?, ?, ?, ?, ?)";

            $stmtOrder = mysqli_prepare($conn, $sqlOrder);
            if (!$stmtOrder) {
                throw new Exception("Lỗi prepare don_hang: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmtOrder,
                "iidddsssii",   // 10 tham số
                $ma_kh,
                $ma_pt,
                $tam_tinh,
                $phi_ship,
                $tong_tien,
                $ten_kh,
                $dia_chi,
                $sdt,
                $tt_thanhtoan,
                $trangthai_don
            );

            if (!mysqli_stmt_execute($stmtOrder)) {
                throw new Exception("Không thể tạo đơn hàng: " . mysqli_error($conn));
            }

            $ma_dh = mysqli_insert_id($conn);

            // ========== CHÈN CHI TIẾT ĐƠN ==========
            $sqlDetail = "INSERT INTO chitiet_dh
                (ma_dh, ma_bienthe_sp, ten_sp, mau, size, so_luong, don_gia, tong_tien)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtDetail = mysqli_prepare($conn, $sqlDetail);
            if (!$stmtDetail) {
                throw new Exception("Lỗi prepare chitiet_dh: " . mysqli_error($conn));
            }

            foreach ($cart as $item) {
                // nếu giỏ hàng KHÔNG có ma_bienthe_sp thì cho NULL
               // nếu giỏ hàng không có ma_bienthe_sp thì gán 0
$ma_bienthe = !empty($item['ma_bienthe_sp'])
    ? (int)$item['ma_bienthe_sp']
    : 0;   // <= quan trọng: không còn NULL nữa

                $ten_sp   = $item['ten_sp'] ?? '';
                $mau      = $item['mau'] ?? '';
                $size     = $item['size'] ?? '';
                $so_luong = (int)($item['qty'] ?? 1);
                $don_gia  = (int)$item['gia'];
                $tong_ct  = $so_luong * $don_gia;

                mysqli_stmt_bind_param(
                    $stmtDetail,
                    "iisssidd",
                    $ma_dh,
                    $ma_bienthe,
                    $ten_sp,
                    $mau,
                    $size,
                    $so_luong,
                    $don_gia,
                    $tong_ct
                );

                if (!mysqli_stmt_execute($stmtDetail)) {
                    throw new Exception("Không thể tạo chi tiết đơn hàng: " . mysqli_error($conn));
                }
            }

            // Thành công
            mysqli_commit($conn);
            unset($_SESSION['cart']);   // Xoá giỏ hàng

            if ($payMethod === 'vnpay') {
                $bank = urlencode('NCB');
                header("Location: payment/vnpay_create_payment.php?order_id={$ma_dh}&amount={$tong_tien}&bank_code={$bank}");
                exit;
            } else {
                header("Location: orders.php");
                exit;
            }

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errors = "Có lỗi khi tạo đơn hàng: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <style>
        body {font-family: Arial, sans-serif; background:#f5f5f5;}
        .container {max-width:900px;margin:20px auto;background:#fff;padding:20px;border-radius:10px;display:flex;gap:20px;}
        .col {flex:1;}
        h2 {margin-bottom:10px;}
        table {width:100%;border-collapse: collapse;font-size:14px;}
        th,td {border:1px solid #ddd;padding:6px;}
        th {background:#f0f0f0;}
        .error {margin-bottom:10px;padding:8px;background:#ffebee;color:#c62828;border-radius:6px;}
        .btn {display:inline-block;padding:10px 16px;border-radius:6px;border:none;cursor:pointer;}
        .btn-primary {background:#007bff;color:#fff;}
        .btn-secondary {background:#ccc;color:#333;}
        .total {font-weight:bold;color:#d32f2f;}
    </style>
</head>
<body>
<div class="container">
    <div class="col">
        <h2>Thông tin giao hàng</h2>
        <?php if ($errors): ?>
            <div class="error"><?php echo htmlspecialchars($errors); ?></div>
        <?php endif; ?>

        <form method="post">
            <div>
                <label>Họ tên</label><br>
                <input type="text" name="ten_kh" style="width:100%;padding:6px"
                       value="<?php echo htmlspecialchars($user['HoTen']); ?>">
            </div>
            <div>
                <label>Địa chỉ</label><br>
                <input type="text" name="dia_chi" style="width:100%;padding:6px">
            </div>
            <div>
                <label>Số điện thoại</label><br>
                <input type="text" name="sdt" style="width:100%;padding:6px"
                       value="<?php echo htmlspecialchars($user['SDT']); ?>">
            </div>

            <h3>Phương thức thanh toán</h3>
            <label>
                <input type="radio" name="payment_method" value="cod" checked>
                Thanh toán khi nhận hàng (COD)
            </label><br>
            <label>
                <input type="radio" name="payment_method" value="vnpay">
                Thanh toán online qua VNPAY
            </label>

            <div style="margin-top:15px;">
                <button type="submit" class="btn btn-primary">Đặt hàng</button>
                <a href="shoping-cart.php" class="btn btn-secondary">Về giỏ hàng</a>
            </div>
        </form>
    </div>

    <div class="col">
        <h2>Đơn hàng của bạn</h2>
        <table>
            <tr>
                <th>Sản phẩm</th>
                <th>SL</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
            <?php foreach ($cart as $item): 
                $qty = (int)($item['qty'] ?? 1);
                $gia = (int)$item['gia'];
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['ten_sp']); ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo number_format($gia,0,",","."); ?> đ</td>
                    <td><?php echo number_format($gia*$qty,0,",","."); ?> đ</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align:right;">Tạm tính</td>
                <td><?php echo number_format($tam_tinh,0,",","."); ?> đ</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;">Phí ship</td>
                <td><?php echo number_format($phi_ship,0,",","."); ?> đ</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;" class="total">Tổng cộng</td>
                <td class="total"><?php echo number_format($tong_tien,0,",","."); ?> đ</td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
