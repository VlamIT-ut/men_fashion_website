<?php
session_start();
include "db.php";

//==================================================
// 1. BẮT BUỘC ĐĂNG NHẬP
//==================================================
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$ma_kh = $_SESSION["user"]["id"];   // từ login.php: "id" => $row["ma_kh"]
$msg   = "";
$error = "";

//==================================================
// 2. XỬ LÝ HỦY ĐƠN (GET ?cancel=ID)
//   Quy ước: trangthai_don = 3  ==> ĐÃ HỦY
//   Chỉ cho hủy nếu: 
//      - Đơn thuộc về khách hàng hiện tại
//      - Đang ở trạng thái 0 (Chờ xác nhận)
//      - Chưa thanh toán (trangthai_thanhtoan = 0)
//==================================================
if (isset($_GET['cancel'])) {
    $cancel_id = (int) $_GET['cancel'];

    $sqlCancel = "UPDATE don_hang
                  SET trangthai_don = 3
                  WHERE ma_dh = ?
                    AND ma_kh = ?
                    AND trangthai_don = 0
                    AND trangthai_thanhtoan = 0";

    $stmt = mysqli_prepare($conn, $sqlCancel);
    mysqli_stmt_bind_param($stmt, "ii", $cancel_id, $ma_kh);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $msg = "Hủy đơn #$cancel_id thành công.";
    } else {
        $error = "Không thể hủy đơn này (đã xử lý hoặc đã thanh toán).";
    }
}

//==================================================
// 3. PHÂN TRANG DANH SÁCH ĐƠN
//==================================================
$per_page = 5; // số đơn mỗi trang
$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset   = ($page - 1) * $per_page;

// Tổng số đơn của khách
$sqlCount = "SELECT COUNT(*) AS total
             FROM don_hang
             WHERE ma_kh = ?";
$stmtCount = mysqli_prepare($conn, $sqlCount);
mysqli_stmt_bind_param($stmtCount, "i", $ma_kh);
mysqli_stmt_execute($stmtCount);
$resultCount = mysqli_stmt_get_result($stmtCount);
$rowCount    = mysqli_fetch_assoc($resultCount);
$totalOrders = (int)$rowCount["total"];
$totalPages  = ($totalOrders > 0) ? ceil($totalOrders / $per_page) : 1;

// Lấy danh sách đơn theo trang
$sqlOrders = "SELECT ma_dh,
                     ngay_tao,
                     tong_tien,
                     trangthai_thanhtoan,
                     trangthai_don
              FROM don_hang
              WHERE ma_kh = ?
              ORDER BY ngay_tao DESC
              LIMIT ?, ?";
$stmtOrders = mysqli_prepare($conn, $sqlOrders);
mysqli_stmt_bind_param($stmtOrders, "iii", $ma_kh, $offset, $per_page);
mysqli_stmt_execute($stmtOrders);
$orders = mysqli_stmt_get_result($stmtOrders);

//==================================================
// 4. XEM CHI TIẾT ĐƠN (GET ?view=ID)
//==================================================
$orderDetail   = null;
$orderItems    = [];
$view_order_id = null;

if (isset($_GET['view'])) {
    $view_order_id = (int)$_GET['view'];

    // Lấy thông tin đơn (header) – đảm bảo thuộc về khách hiện tại
    $sqlDetail = "SELECT *
                  FROM don_hang
                  WHERE ma_dh = ? AND ma_kh = ?
                  LIMIT 1";
    $stmtDetail = mysqli_prepare($conn, $sqlDetail);
    mysqli_stmt_bind_param($stmtDetail, "ii", $view_order_id, $ma_kh);
    mysqli_stmt_execute($stmtDetail);
    $resultDetail = mysqli_stmt_get_result($stmtDetail);
    if ($orderDetail = mysqli_fetch_assoc($resultDetail)) {
        // Lấy danh sách sản phẩm trong đơn
        $sqlItems = "SELECT *
                     FROM chitiet_dh
                     WHERE ma_dh = ?";
        $stmtItems = mysqli_prepare($conn, $sqlItems);
        mysqli_stmt_bind_param($stmtItems, "i", $view_order_id);
        mysqli_stmt_execute($stmtItems);
        $resultItems = mysqli_stmt_get_result($stmtItems);

        while ($rowItem = mysqli_fetch_assoc($resultItems)) {
            $orderItems[] = $rowItem;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="css/main.css"> <!-- nếu dùng -->

    <style>
        body {font-family: Arial, sans-serif; background:#f5f5f5;}
        .container {max-width: 1000px; margin: 40px auto; background:#fff; padding:24px; border-radius:10px;}
        h1 {font-size:22px; margin-bottom:10px;}

        .back-home {
            display:inline-block;
            margin-bottom:15px;
            padding:8px 14px;
            background:#7c4dff;
            color:#fff;
            border-radius:6px;
            text-decoration:none;
            font-size:14px;
            transition: .2s;
        }
        .back-home:hover {background:#5a35d9;}

        .alert-msg {
            padding:8px 12px;
            border-radius:8px;
            font-size:13px;
            margin-bottom:15px;
        }
        .alert-success {background:#e8f5e9; color:#2e7d32; border:1px solid #c8e6c9;}
        .alert-error   {background:#ffebee; color:#c62828; border:1px solid #ffcdd2;}

        table {width:100%; border-collapse: collapse; margin-top:10px;}
        th, td {border:1px solid #ddd; padding:8px; font-size:14px; text-align:center;}
        th {background:#f0f0f0;}

        /* Badge trạng thái */
        .badge {
            display:inline-block;
            padding:3px 9px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
        }
        .badge-paid      {background:#e8f5e9; color:#2e7d32;}
        .badge-unpaid    {background:#ffebee; color:#c62828;}
        .badge-pending   {background:#fff3e0; color:#ef6c00;}
        .badge-shipping  {background:#e3f2fd; color:#1565c0;}
        .badge-done      {background:#e8f5e9; color:#2e7d32;}
        .badge-cancel    {background:#eceff1; color:#455a64;}

        /* Nút xem / hủy */
        .btn {
            display:inline-block;
            padding:5px 10px;
            border-radius:6px;
            font-size:12px;
            text-decoration:none;
            border:none;
            cursor:pointer;
            transition:.2s;
        }
        .btn-detail {background:#1976d2; color:#fff;}
        .btn-detail:hover {background:#0d47a1;}
        .btn-cancel {background:#ef5350; color:#fff;}
        .btn-cancel:hover {background:#c62828;}

        /* Phân trang */
        .pagination {
            margin-top:15px;
            text-align:center;
        }
        .pagination a, .pagination span {
            display:inline-block;
            margin:0 3px;
            padding:5px 9px;
            border-radius:4px;
            font-size:13px;
            text-decoration:none;
            border:1px solid #ddd;
            color:#555;
        }
        .pagination .active {
            background:#7c4dff;
            color:#fff;
            border-color:#7c4dff;
        }
        .pagination .disabled {
            opacity:.5;
            cursor:default;
        }

        /* Khối chi tiết đơn */
        .order-detail {
            margin-top:30px;
            padding-top:15px;
            border-top:2px solid #eee;
        }
        .order-detail h2 {
            font-size:18px;
            margin-bottom:10px;
        }
        .order-meta {
            font-size:13px;
            margin-bottom:5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Đơn hàng của tôi</h1>
    <a href="index.php" class="back-home">← Về trang chủ</a>

    <?php if ($msg != ""): ?>
        <div class="alert-msg alert-success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <?php if ($error != ""): ?>
        <div class="alert-msg alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Mã đơn</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái đơn</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($orders) == 0): ?>
            <tr>
                <td colspan="6">Bạn chưa có đơn hàng nào.</td>
            </tr>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                <?php
                    $id_don   = (int)$row["ma_dh"];
                    $paid     = (int)$row["trangthai_thanhtoan"];
                    $st_don   = (int)$row["trangthai_don"];
                ?>
                <tr>
                    <td>#<?php echo $id_don; ?></td>
                    <td><?php echo htmlspecialchars($row["ngay_tao"]); ?></td>
                    <td><?php echo number_format($row["tong_tien"], 0, ",", "."); ?> đ</td>

                    <td>
                        <?php if ($paid == 1): ?>
                            <span class="badge badge-paid">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge badge-unpaid">Chưa thanh toán</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php
                        switch ($st_don) {
                            case 0:
                                echo '<span class="badge badge-pending">Chờ xác nhận</span>';
                                break;
                            case 1:
                                echo '<span class="badge badge-shipping">Đang giao</span>';
                                break;
                            case 2:
                                echo '<span class="badge badge-done">Hoàn thành</span>';
                                break;
                            case 3:
                                echo '<span class="badge badge-cancel">Đã hủy</span>';
                                break;
                            default:
                                echo '<span class="badge badge-cancel">Không xác định</span>';
                        }
                        ?>
                    </td>

                    <td>
                        <!-- Xem chi tiết -->
                        <a class="btn btn-detail"
                           href="orders.php?view=<?php echo $id_don; ?>&page=<?php echo $page; ?>">
                            Xem chi tiết
                        </a>

                        <!-- Hủy đơn – chỉ hiện khi chờ xác nhận & chưa thanh toán -->
                        <?php if ($st_don == 0 && $paid == 0): ?>
                            <a class="btn btn-cancel"
                               href="orders.php?cancel=<?php echo $id_don; ?>&page=<?php echo $page; ?>"
                               onclick="return confirm('Bạn chắc chắn muốn hủy đơn #<?php echo $id_don; ?>?');">
                                Hủy đơn
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- PHÂN TRANG -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="orders.php?page=<?php echo $page - 1; ?>">&laquo; Trước</a>
            <?php else: ?>
                <span class="disabled">&laquo; Trước</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="orders.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="orders.php?page=<?php echo $page + 1; ?>">Sau &raquo;</a>
            <?php else: ?>
                <span class="disabled">Sau &raquo;</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- CHI TIẾT ĐƠN: DANH SÁCH SẢN PHẨM -->
    <?php if ($orderDetail && !empty($orderItems)): ?>
        <div class="order-detail">
            <h2>Chi tiết đơn #<?php echo (int)$orderDetail["ma_dh"]; ?></h2>
            <div class="order-meta">
                Ngày đặt: <strong><?php echo htmlspecialchars($orderDetail["ngay_tao"]); ?></strong>
            </div>
            <div class="order-meta">
                Tổng tiền: <strong><?php echo number_format($orderDetail["tong_tien"], 0, ",", "."); ?> đ</strong>
            </div>

            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Màu</th>
                    <th>Size</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <?php $stt = 1; ?>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><?php echo htmlspecialchars($item["ten_sp"]); ?></td>
                        <td><?php echo htmlspecialchars($item["mau"]); ?></td>
                        <td><?php echo htmlspecialchars($item["size"]); ?></td>
                        <td><?php echo (int)$item["so_luong"]; ?></td>
                        <td><?php echo number_format($item["don_gia"], 0, ",", "."); ?> đ</td>
                        <td><?php echo number_format($item["tong_tien"], 0, ",", "."); ?> đ</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($view_order_id && !$orderDetail): ?>
        <div class="order-detail">
            <div class="alert-msg alert-error">
                Không tìm thấy đơn hàng #<?php echo (int)$view_order_id; ?> hoặc không thuộc về tài khoản của bạn.
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
