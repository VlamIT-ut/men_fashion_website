<?php
include "admin_header.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$thongBao   = "";
$editId     = 0;
$editProduct = null;

// ===================== LẤY DANH SÁCH LOẠI SẢN PHẨM =====================
$dsLoai = [];
$sqlLoai = "SELECT ma_loaisp, ten_loai FROM loai_sp ORDER BY ma_loaisp";
$rsLoai  = mysqli_query($conn, $sqlLoai);
while ($row = mysqli_fetch_assoc($rsLoai)) {
    $dsLoai[] = $row;
}

// ===================== ẨN / HIỆN SẢN PHẨM =====================
if (isset($_GET['toggle'])) {
    $id     = (int)$_GET['toggle'];
    $status = (int)($_GET['status'] ?? 0); // 0 hoặc 1

    $newStatus = $status ? "b'1'" : "b'0'";

    mysqli_query($conn,
        "UPDATE san_pham SET ton_tai = $newStatus WHERE ma_sp = $id"
    );

    header("Location: products.php");
    exit;
}

// ===================== XÓA SẢN PHẨM =====================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query($conn, "DELETE FROM hinhanh_sp WHERE ma_sp = $id");
    mysqli_query($conn, "DELETE FROM san_pham WHERE ma_sp = $id");

    $thongBao = "Đã xóa sản phẩm mã $id";
}

// ===================== LẤY DỮ LIỆU ĐỂ SỬA (NẾU CÓ edit=) =====================
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $sqlOne = "SELECT * FROM san_pham WHERE ma_sp = $editId";
    $rsOne  = mysqli_query($conn, $sqlOne);
    if ($rsOne && mysqli_num_rows($rsOne) > 0) {
        $editProduct = mysqli_fetch_assoc($rsOne);
    } else {
        $editId = 0;
    }
}

// ===================== THÊM SẢN PHẨM =====================
if (isset($_POST['saveProduct'])) {

    $ten       = trim($_POST['ten_sp'] ?? "");
    $gia       = (float)($_POST['gia'] ?? 0);
    $mota      = trim($_POST['mota'] ?? "");
    $so_luong  = (int)($_POST['so_luong'] ?? 0);
    $ma_loaisp = (int)($_POST['ma_loaisp'] ?? 0);

    if ($ten == "" || $gia <= 0 || $ma_loaisp <= 0) {
        $thongBao = "Tên, giá và loại sản phẩm không được trống!";
    } else {

        $sql = "INSERT INTO san_pham (ma_loaisp, ten_sp, gia, mota, so_luong, ton_tai)
                VALUES (?, ?, ?, ?, ?, b'1')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isdsi",
            $ma_loaisp, $ten, $gia, $mota, $so_luong
        );
        mysqli_stmt_execute($stmt);

        $ma_sp = mysqli_insert_id($conn);

        // UPLOAD HÌNH
        if (!empty($_FILES['hinh']['name'][0])) {
            foreach ($_FILES['hinh']['name'] as $i => $filename) {
                if ($_FILES['hinh']['error'][$i] == 0) {
                    $tmp      = $_FILES['hinh']['tmp_name'][$i];
                    $fileName = basename($filename);
                    $savePath = "../images/products/" . $fileName;

                    move_uploaded_file($tmp, $savePath);

                    mysqli_query($conn,
                        "INSERT INTO hinhanh_sp (ma_sp, ten_anh)
                         VALUES ($ma_sp, '" . mysqli_real_escape_string($conn, $fileName) . "')"
                    );
                }
            }
        }

        header("Location: products.php");
        exit;
    }
}

// ===================== CẬP NHẬT (SỬA) SẢN PHẨM =====================
if (isset($_POST['updateProduct'])) {

    $ma_sp     = (int)($_POST['ma_sp'] ?? 0);
    $ten       = trim($_POST['ten_sp'] ?? "");
    $gia       = (float)($_POST['gia'] ?? 0);
    $mota      = trim($_POST['mota'] ?? "");
    $so_luong  = (int)($_POST['so_luong'] ?? 0);
    $ma_loaisp = (int)($_POST['ma_loaisp'] ?? 0);

    if ($ma_sp <= 0 || $ten == "" || $gia <= 0 || $ma_loaisp <= 0) {
        $thongBao = "Thiếu thông tin khi cập nhật!";
    } else {

        $sql = "UPDATE san_pham 
                SET ma_loaisp = ?, ten_sp = ?, gia = ?, mota = ?, so_luong = ?
                WHERE ma_sp = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isdsii",
            $ma_loaisp, $ten, $gia, $mota, $so_luong, $ma_sp
        );
        mysqli_stmt_execute($stmt);

       // Nếu có upload ảnh mới => XÓA ảnh cũ + CHỈ LƯU ảnh mới
        if (!empty($_FILES['hinh']['name'][0])) {

            // 1. Lấy danh sách ảnh cũ để xóa file trong thư mục (nếu muốn)
            $rsOld = mysqli_query($conn, "SELECT ten_anh FROM hinhanh_sp WHERE ma_sp = $ma_sp");
            while ($old = mysqli_fetch_assoc($rsOld)) {
                $oldPath = "../images/products/" . $old['ten_anh'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath); // xóa file ảnh cũ
                }
            }

            // 2. Xóa bản ghi ảnh cũ trong DB
            mysqli_query($conn, "DELETE FROM hinhanh_sp WHERE ma_sp = $ma_sp");

            // 3. Thêm các ảnh mới
            foreach ($_FILES['hinh']['name'] as $i => $filename) {
                if ($_FILES['hinh']['error'][$i] == 0) {
                    $tmp      = $_FILES['hinh']['tmp_name'][$i];
                    $fileName = basename($filename);
                    $savePath = "../images/products/" . $fileName;

                    move_uploaded_file($tmp, $savePath);

                    mysqli_query($conn,
                        "INSERT INTO hinhanh_sp (ma_sp, ten_anh)
                         VALUES ($ma_sp, '" . mysqli_real_escape_string($conn, $fileName) . "')"
                    );
                }
            }
        }

        header("Location: products.php");
        exit;
    }
}

// ===================== LẤY DANH SÁCH SẢN PHẨM =====================
$sql = "
SELECT 
    sp.ma_sp, sp.ten_sp, sp.gia, sp.so_luong,
    l.ten_loai,
    CASE WHEN sp.ton_tai = b'1' THEN 1 ELSE 0 END AS ton_tai,
    (SELECT ha.ten_anh FROM hinhanh_sp ha 
     WHERE ha.ma_sp = sp.ma_sp LIMIT 1) AS hinh
FROM san_pham sp
LEFT JOIN loai_sp l ON sp.ma_loaisp = l.ma_loaisp
ORDER BY sp.ma_sp DESC
";
$result = mysqli_query($conn, $sql);

// ===================== GIÁ TRỊ MẶC ĐỊNH CHO FORM (THÊM / SỬA) =====================
$formTitle   = $editProduct ? "Sửa sản phẩm" : "Thêm sản phẩm mới";
$submitName  = $editProduct ? "updateProduct" : "saveProduct";
$submitLabel = $editProduct ? "Cập nhật" : "Lưu";

$val_ma_sp    = $editProduct['ma_sp']       ?? "";
$val_ten_sp   = $editProduct['ten_sp']      ?? "";
$val_gia      = $editProduct['gia']         ?? "";
$val_mota     = $editProduct['mota']        ?? "";
$val_so_luong = $editProduct['so_luong']    ?? 0;
$val_ma_loai  = $editProduct['ma_loaisp']   ?? "";
?>

<h3 class="mb-4">Quản lý sản phẩm</h3>

<?php if ($thongBao != ""): ?>
    <div class="alert alert-info"><?php echo $thongBao; ?></div>
<?php endif; ?>

<!-- FORM THÊM / SỬA -->
<div class="card mb-4">
    <div class="card-header"><?php echo $formTitle; ?></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

            <?php if ($editProduct): ?>
                <input type="hidden" name="ma_sp" value="<?php echo $val_ma_sp; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="ten_sp" class="form-control"
                       value="<?php echo htmlspecialchars($val_ten_sp); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Loại sản phẩm</label>
                <select name="ma_loaisp" class="form-control" required>
                    <option value="">-- Chọn loại sản phẩm --</option>
                    <?php foreach ($dsLoai as $loai): ?>
                        <option value="<?php echo $loai['ma_loaisp']; ?>"
                            <?php if ($val_ma_loai == $loai['ma_loaisp']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($loai['ten_loai']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" name="gia" class="form-control"
                       value="<?php echo htmlspecialchars($val_gia); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mota" class="form-control" rows="4"><?php
                    echo htmlspecialchars($val_mota);
                ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="so_luong" class="form-control"
                       min="0" value="<?php echo (int)$val_so_luong; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Hình ảnh (có thể chọn nhiều ảnh)
                    <?php if ($editProduct): ?>
                        <small class="text-muted">(nếu không chọn thì giữ nguyên ảnh cũ)</small>
                    <?php endif; ?>
                </label>
                <input type="file" name="hinh[]" class="form-control" multiple>
            </div>

            <button type="submit" name="<?php echo $submitName; ?>" class="btn btn-primary">
                <?php echo $submitLabel; ?>
            </button>

            <?php if ($editProduct): ?>
                <a href="products.php" class="btn btn-secondary ms-2">Hủy sửa</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- DANH SÁCH -->
<div class="card">
    <div class="card-header">Danh sách sản phẩm</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Mã</th>
                <th>Tên</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Trạng thái</th>
                <th>Hình</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>

            <?php while ($row = mysqli_fetch_assoc($result)): 
                $tonTai = (int)$row['ton_tai'];
            ?>
                <tr>
                    <td><?php echo $row['ma_sp']; ?></td>
                    <td><?php echo htmlspecialchars($row['ten_sp']); ?></td>
                    <td><?php echo htmlspecialchars($row['ten_loai']); ?></td>
                    <td><?php echo number_format($row['gia']); ?> ₫</td>
                    <td><?php echo (int)$row['so_luong']; ?></td>
                    <td>
                        <?php if ($tonTai): ?>
                            <span class="badge bg-success">Đang hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Đang ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['hinh'] != ""): ?>
                            <img src="../images/products/<?php echo $row['hinh']; ?>"
                                 style="width:60px;height:auto;">
                        <?php else: ?>
                            <span class="text-muted">Chưa có hình</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="products.php?edit=<?php echo $row['ma_sp']; ?>"
                           class="btn btn-sm btn-info mb-1">
                            Sửa
                        </a>

                        <a href="products.php?toggle=<?php echo $row['ma_sp']; ?>&status=<?php echo $tonTai ? 0 : 1; ?>"
                           class="btn btn-sm btn-warning mb-1">
                            <?php echo $tonTai ? "Ẩn" : "Hiện"; ?>
                        </a>

                        <a href="products.php?delete=<?php echo $row['ma_sp']; ?>"
                           class="btn btn-sm btn-danger mb-1"
                           onclick="return confirm('Xóa sản phẩm?');">
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
    </div>
</div>

</div></main></div></div>
</body>
</html>
