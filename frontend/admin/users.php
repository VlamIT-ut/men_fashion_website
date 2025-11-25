<?php
include "admin_header.php";

$sql = "
    SELECT 
        ma_kh AS id,
        ho_ten AS username,
        email,
        sdt   AS dien_thoai,
        trang_thai
    FROM khach_hang
    ORDER BY ma_kh DESC
";
$rsUsers = mysqli_query($conn, $sql);
?>


<h3 class="mb-4">Quản lý người dùng</h3>

<div class="card">
    <div class="card-header">
        Danh sách người dùng
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
               <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($u = mysqli_fetch_assoc($rsUsers)): ?>
                <tr>
                   <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['dien_thoai']); ?></td>
                     <td>
                        <?php echo $u['trang_thai'] ? 'Hoạt động' : 'Khóa'; ?>
                    </td>
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
