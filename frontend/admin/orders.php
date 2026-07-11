<?php
include "admin_header.php";

$thongBao = "";

// ===================== XỬ LÝ HÀNH ĐỘNG CẬP NHẬT TRẠNG THÁI =====================
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = trim($_GET['action']);
    $id = (int)$_GET['id'];

    if ($action === 'duyet') {
        // Duyệt đơn -> chuyển trạng thái sang 1 (Đang giao)
        $sqlUp = "UPDATE don_hang SET trangthai_don = 1 WHERE ma_dh = ?";
        $stmt = mysqli_prepare($conn, $sqlUp);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $thongBao = "Đã duyệt đơn hàng #$id thành công!";
        }
    } elseif ($action === 'hoanthanh') {
        // Hoàn thành đơn -> chuyển trạng thái sang 2 (Hoàn thành) và đánh dấu đã thanh toán (1)
        $sqlUp = "UPDATE don_hang SET trangthai_don = 2, trangthai_thanhtoan = 1 WHERE ma_dh = ?";
        $stmt = mysqli_prepare($conn, $sqlUp);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $thongBao = "Đơn hàng #$id đã hoàn thành và thanh toán!";
        }
    } elseif ($action === 'huy') {
        // Hủy đơn -> chuyển trạng thái sang 3 (Đã hủy)
        $sqlUp = "UPDATE don_hang SET trangthai_don = 3 WHERE ma_dh = ?";
        $stmt = mysqli_prepare($conn, $sqlUp);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $thongBao = "Đã hủy đơn hàng #$id!";
        }
    } elseif ($action === 'toggle_payment') {
        // Đổi trạng thái thanh toán qua lại giữa 0 và 1
        $sqlUp = "UPDATE don_hang SET trangthai_thanhtoan = 1 - trangthai_thanhtoan WHERE ma_dh = ?";
        $stmt = mysqli_prepare($conn, $sqlUp);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $thongBao = "Đã cập nhật trạng thái thanh toán đơn #$id!";
        }
    }
}

// ===================== LẤY CHI TIẾT ĐƠN HÀNG ĐỂ XEM =====================
$viewOrderId = isset($_GET['view']) ? (int)$_GET['view'] : 0;
$orderDetails = null;
$orderItems = [];

if ($viewOrderId > 0) {
    $sqlOrder = "SELECT * FROM don_hang WHERE ma_dh = ? LIMIT 1";
    $stmtOrder = mysqli_prepare($conn, $sqlOrder);
    mysqli_stmt_bind_param($stmtOrder, "i", $viewOrderId);
    mysqli_stmt_execute($stmtOrder);
    $resOrder = mysqli_stmt_get_result($stmtOrder);
    $orderDetails = mysqli_fetch_assoc($resOrder);

    if ($orderDetails) {
        $sqlItems = "SELECT * FROM chitiet_dh WHERE ma_dh = ?";
        $stmtItems = mysqli_prepare($conn, $sqlItems);
        mysqli_stmt_bind_param($stmtItems, "i", $viewOrderId);
        mysqli_stmt_execute($stmtItems);
        $resItems = mysqli_stmt_get_result($stmtItems);
        while ($item = mysqli_fetch_assoc($resItems)) {
            $orderItems[] = $item;
        }
    }
}

// ===================== LẤY TOÀN BỘ DANH SÁCH ĐƠN HÀNG =====================
$sql = "
    SELECT 
        ma_dh AS id,
        ma_kh,
        ten_kh,
        sdt,
        dia_chi,
        tong_tien,
        ngay_tao AS ngay_dat,
        trangthai_thanhtoan AS thanh_toan,
        trangthai_don AS trang_thai
    FROM don_hang
    ORDER BY ma_dh DESC
";
$rsOrders = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý đơn hàng</h3>
</div>

<?php if ($thongBao != ""): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($thongBao); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- CỘT DANH SÁCH ĐƠN HÀNG -->
    <div class="<?php echo $orderDetails ? 'col-lg-7' : 'col-lg-12'; ?>">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái đơn</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = mysqli_fetch_assoc($rsOrders)): ?>
                                <?php 
                                    $activeRow = ($o['id'] == $viewOrderId) ? 'table-primary' : '';
                                ?>
                                <tr class="<?php echo $activeRow; ?>">
                                    <td><strong>#<?php echo $o['id']; ?></strong></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($o['ten_kh']); ?></div>
                                        <small class="text-muted">Mã KH: <?php echo $o['ma_kh']; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($o['sdt']); ?></td>
                                    <td class="fw-bold"><?php echo number_format($o['tong_tien'], 0, ',', '.'); ?>₫</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($o['ngay_dat'])); ?></td>
                                    <td>
                                        <?php if ((int)$o['thanh_toan'] === 1): ?>
                                            <a href="orders.php?action=toggle_payment&id=<?php echo $o['id']; ?>" class="badge bg-success text-decoration-none px-2 py-1.5" title="Bấm để đổi trạng thái">Đã thanh toán</a>
                                        <?php else: ?>
                                            <a href="orders.php?action=toggle_payment&id=<?php echo $o['id']; ?>" class="badge bg-danger text-decoration-none px-2 py-1.5" title="Bấm để đổi trạng thái">Chưa thanh toán</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        switch ((int)$o['trang_thai']) {
                                            case 0:
                                                echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1.5">Chờ xác nhận</span>';
                                                break;
                                            case 1:
                                                echo '<span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1.5">Đang giao</span>';
                                                break;
                                            case 2:
                                                echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1.5">Hoàn thành</span>';
                                                break;
                                            case 3:
                                                echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1.5">Đã hủy</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="orders.php?view=<?php echo $o['id']; ?>" class="btn btn-sm btn-light border" title="Xem chi tiết">
                                                🔍
                                            </a>
                                            <?php if ((int)$o['trang_thai'] === 0): ?>
                                                <a href="orders.php?action=duyet&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary" title="Duyệt đơn">
                                                    Duyệt
                                                </a>
                                                <a href="orders.php?action=huy&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')" title="Hủy đơn">
                                                    Hủy
                                                </a>
                                            <?php elseif ((int)$o['trang_thai'] === 1): ?>
                                                <a href="orders.php?action=hoanthanh&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-success" title="Hoàn thành đơn">
                                                    Xong
                                                </a>
                                                <a href="orders.php?action=huy&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')" title="Hủy đơn">
                                                    Hủy
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- CỘT CHI TIẾT ĐƠN HÀNG (HIỂN THỊ KHI BẤM XEM CHI TIẾT) -->
    <?php if ($orderDetails): ?>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 10;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Chi tiết đơn hàng #<?php echo $orderDetails['ma_dh']; ?></h5>
                    <a href="orders.php" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="card-body">
                    <!-- Thông tin khách hàng & Giao nhận -->
                    <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Thông tin giao hàng</h6>
                    <table class="table table-sm table-borderless mb-4" style="font-size: 13px;">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Người nhận:</td>
                            <td class="fw-bold"><?php echo htmlspecialchars($orderDetails['ten_kh']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Số điện thoại:</td>
                            <td><?php echo htmlspecialchars($orderDetails['sdt']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Địa chỉ giao:</td>
                            <td><?php echo htmlspecialchars($orderDetails['dia_chi']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày đặt:</td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($orderDetails['ngay_tao'])); ?></td>
                        </tr>
                    </table>

                    <!-- Trạng thái hiện tại -->
                    <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Trạng thái hiện tại</h6>
                    <div class="d-flex gap-2 mb-4">
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Đơn hàng:</span>
                            <?php
                            switch ((int)$orderDetails['trangthai_don']) {
                                case 0:
                                    echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">Chờ xác nhận</span>';
                                    break;
                                case 1:
                                    echo '<span class="badge bg-info-subtle text-info border border-info-subtle">Đang giao</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge bg-success-subtle text-success border border-success-subtle">Hoàn thành</span>';
                                    break;
                                case 3:
                                    echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Đã hủy</span>';
                                    break;
                            }
                            ?>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Thanh toán:</span>
                            <?php if ((int)$orderDetails['trangthai_thanhtoan'] === 1): ?>
                                <span class="badge bg-success">Đã thanh toán</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Chưa thanh toán</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <h6 class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Sản phẩm đã mua</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($item['ten_sp']); ?></div>
                                            <div class="text-muted" style="font-size: 11px;">Màu: <?php echo htmlspecialchars($item['mau']); ?> | Size: <?php echo htmlspecialchars($item['size']); ?></div>
                                        </td>
                                        <td class="text-center"><?php echo $item['so_luong']; ?></td>
                                        <td class="text-end"><?php echo number_format($item['don_gia'], 0, ',', '.'); ?>₫</td>
                                        <td class="text-end fw-bold"><?php echo number_format($item['tong_tien'], 0, ',', '.'); ?>₫</td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-2">Tạm tính:</td>
                                    <td class="text-end py-2"><?php echo number_format($orderDetails['tam_tinh'], 0, ',', '.'); ?>₫</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-muted py-1">Phí vận chuyển:</td>
                                    <td class="text-end text-muted py-1"><?php echo number_format($orderDetails['phi_ship'], 0, ',', '.'); ?>₫</td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold py-2">TỔNG CỘNG:</td>
                                    <td class="text-end fw-bold text-danger py-2" style="font-size: 14px;"><?php echo number_format($orderDetails['tong_tien'], 0, ',', '.'); ?>₫</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// đóng layout
?>
        </div>
    </main>
</div>
</div>

</body>
</html>
