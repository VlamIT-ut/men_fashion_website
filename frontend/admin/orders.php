<?php
include "admin_header.php";

$sql = "
    SELECT 
        ma_dh  AS id,
        ma_kh,
        tong_tien,
        ngay_tao     AS ngay_dat,
        trangthai_don AS trang_thai
    FROM don_hang
    ORDER BY ma_dh DESC
";
$rsOrders = mysqli_query($conn, $sql);
?>

<h3 class="mb-4">Quản lý đơn hàng</h3>

<div class="card">
    <div class="card-header">
        Danh sách đơn hàng
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
               <th>ID</th>
                <th>Mã KH</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($o = mysqli_fetch_assoc($rsOrders)): ?>
                <tr>
                      <td><?php echo $o['id']; ?></td>
                    <td><?php echo $o['ma_kh']; ?></td>
                    <td><?php echo number_format($o['tong_tien']); ?> ₫</td>
                    <td><?php echo $o['ngay_dat']; ?></td>
                    <td><?php echo htmlspecialchars($o['trang_thai']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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
