<?php
include "admin_header.php";

// Số user mỗi trang
$perPage = 10;

// Lấy trang hiện tại từ ?page=...
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Lấy bộ lọc từ GET
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$status = isset($_GET['status']) && $_GET['status'] !== '' ? (int)$_GET['status'] : null;

$whereParts = [];
if ($q !== '') {
    $qEsc = mysqli_real_escape_string($conn, $q);
    $whereParts[] = "(ho_ten LIKE '%$qEsc%' OR email LIKE '%$qEsc%' OR sdt LIKE '%$qEsc%')";
}
if ($status !== null) {
    $whereParts[] = "trang_thai = $status";
}

$whereSQL = "";
if (count($whereParts) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereParts);
}

// Đếm tổng số user để tính số trang
$sqlCount   = "SELECT COUNT(*) AS total FROM khach_hang" . $whereSQL;
$rsCount    = mysqli_query($conn, $sqlCount);
$rowCount   = mysqli_fetch_assoc($rsCount);
$totalUsers = (int)$rowCount['total'];
$totalPages = ($totalUsers > 0) ? ceil($totalUsers / $perPage) : 1;

if ($page > $totalPages) $page = $totalPages;
if ($page < 1) $page = 1;

// Tính vị trí bắt đầu
$offset = ($page - 1) * $perPage;
if ($offset < 0) $offset = 0;

$sql = "
    SELECT 
        ma_kh AS id,
        ho_ten AS username,
        email,
        sdt   AS dien_thoai,
        trang_thai
    FROM khach_hang
    $whereSQL
    ORDER BY ma_kh DESC
    LIMIT $offset, $perPage
";
$rsUsers = mysqli_query($conn, $sql);

// Build filter query string for pagination
$filterParams = "";
if ($q !== '') {
    $filterParams .= "&q=" . urlencode($q);
}
if ($status !== null) {
    $filterParams .= "&status=" . $status;
}
?>


<h3 class="mb-4">Quản lý người dùng</h3>

<form class="row mb-3" method="get">
    <div class="col-md-4">
        <input type="text"
               name="q"
               class="form-control"
               placeholder="Tìm theo tên, email, SĐT"
               value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
    </div>

    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="1" <?php if(($_GET['status'] ?? '') === '1') echo 'selected'; ?>>Hoạt động</option>
            <option value="0" <?php if(($_GET['status'] ?? '') === '0') echo 'selected'; ?>>Đã khóa</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary">Lọc</button>
        <a href="users.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

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
                <th>Thao tác</th>
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
                        <?php if ($u['trang_thai']): ?>
                            <span class="badge bg-success">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Đã khóa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['trang_thai']): ?>
                            <!-- nút khóa -->
                            <a href="admin_user_toggle.php?id=<?php echo $u['id']; ?>&action=lock"
                               class="btn btn-sm btn-outline-warning"
                               onclick="return confirm('Khoá tài khoản này?');">
                                Khoá
                            </a>
                        <?php else: ?>
                            <!-- nút mở khóa -->
                            <a href="admin_user_toggle.php?id=<?php echo $u['id']; ?>&action=unlock"
                               class="btn btn-sm btn-outline-success"
                               onclick="return confirm('Mở khoá tài khoản này?');">
                                Mở khoá
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
            </div> <!-- đóng card-body p-0 -->

    <div class="card-footer">
        <nav>
            <ul class="pagination mb-0">
                <!-- Nút trang trước -->
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $filterParams; ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <!-- Các số trang -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $filterParams; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Nút trang sau -->
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $filterParams; ?>">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div> <!-- đóng .card -->

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
