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
?>

<h3 class="mb-4">Tổng quan hệ thống</h3>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Sản phẩm</h5>
                <p class="display-6 mb-0"><?php echo $totalProducts; ?></p>
                <small class="text-muted">Tổng số sản phẩm trong cửa hàng</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Người dùng</h5>
                <p class="display-6 mb-0"><?php echo $totalUsers; ?></p>
                <small class="text-muted">Tổng số khách hàng</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng</h5>
                <p class="display-6 mb-0"><?php echo $totalOrders; ?></p>
                <small class="text-muted">Tổng số đơn hàng đã tạo</small>
            </div>
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
