<?php
include "admin_header.php";

// Lấy vài thống kê nhỏ
$totalProducts = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM san_pham")
)[0] ?? 0;

$totalUsers = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM khach_hang")
)[0] ?? 0;

$totalOrders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM don_hang")
)[0] ?? 0;

// Tính tổng doanh thu từ các đơn hàng hoàn thành (trangthai_don = 2)
$totalRevenue = mysqli_fetch_row(
    mysqli_query($conn, "SELECT SUM(tong_tien) FROM don_hang WHERE trangthai_don = 2")
)[0] ?? 0;

// Lấy 5 đơn hàng mới nhất
$recentOrdersSQL = "
    SELECT 
        ma_dh AS id,
        ngay_tao AS ngay_dat,
        ten_kh,
        sdt,
        tong_tien,
        trangthai_don AS trang_thai
    FROM don_hang
    ORDER BY ma_dh DESC
    LIMIT 5
";
$recentOrdersResult = mysqli_query($conn, $recentOrdersSQL);
?>

<h3 class="mb-4">Tổng quan hệ thống</h3>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <h6 class="card-title text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 0.5px;">Sản phẩm</h6>
                <p class="h3 font-weight-bold mb-1"><?php echo $totalProducts; ?></p>
                <small class="text-muted">Tổng số sản phẩm</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <h6 class="card-title text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 0.5px;">Người dùng</h6>
                <p class="h3 font-weight-bold mb-1"><?php echo $totalUsers; ?></p>
                <small class="text-muted">Tổng số khách hàng</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <h6 class="card-title text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 0.5px;">Đơn hàng</h6>
                <p class="h3 font-weight-bold mb-1"><?php echo $totalOrders; ?></p>
                <small class="text-muted">Tổng số đơn hàng</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
            <div class="card-body">
                <h6 class="card-title text-muted text-uppercase mb-2" style="font-size: 12px; letter-spacing: 0.5px;">Doanh thu (Đã Giao)</h6>
                <p class="h3 font-weight-bold mb-1 text-success"><?php echo number_format($totalRevenue, 0, ',', '.'); ?>đ</p>
                <small class="text-muted">Từ đơn hoàn thành</small>
            </div>
        </div>
    </div>
</div>

<h4 class="mt-5 mb-3">Đơn hàng mới nhận</h4>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">Mã đơn</th>
                        <th class="py-3">Khách hàng</th>
                        <th class="py-3">Số điện thoại</th>
                        <th class="py-3">Tổng tiền</th>
                        <th class="py-3">Ngày đặt</th>
                        <th class="py-3">Trạng thái</th>
                        <th class="py-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($recentOrdersResult) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($order = mysqli_fetch_assoc($recentOrdersResult)): ?>
                            <tr>
                                <td class="py-3"><strong>#<?php echo $order['id']; ?></strong></td>
                                <td class="py-3"><?php echo htmlspecialchars($order['ten_kh']); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($order['sdt']); ?></td>
                                <td class="py-3"><strong><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</strong></td>
                                <td class="py-3"><?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></td>
                                <td class="py-3">
                                    <?php
                                    switch ((int)$order['trang_thai']) {
                                        case 0:
                                            echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">Chờ xác nhận</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">Đang giao</span>';
                                            break;
                                        case 2:
                                            echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">Hoàn thành</span>';
                                            break;
                                        case 3:
                                            echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Đã hủy</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td class="py-3">
                                    <a href="orders.php?view=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// đóng các thẻ mở ở admin_header.php
?>
        </div> <!-- .p-4 -->
    </main>
</div>
</div>

</body>
</html>
