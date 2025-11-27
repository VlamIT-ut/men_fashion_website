<?php
session_start();
include 'db.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userSession = $_SESSION['user'];
$userId      = (int)$userSession['id'];

$success = "";
$error   = "";

// Lấy thông tin hiện tại
$sql  = "SELECT ma_kh, ho_ten, email, sdt 
         FROM khach_hang 
         WHERE ma_kh = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

if (!$user) {
    die("Không tìm thấy tài khoản của bạn trong hệ thống.");
}

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = trim($_POST['ho_ten'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $sdt    = trim($_POST['sdt'] ?? '');

    // Validate cơ bản
    if ($ho_ten === "" || $email === "" || $sdt === "") {
        $error = "Vui lòng nhập đầy đủ họ tên, email và số điện thoại.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không đúng định dạng.";
    } else {
        // Kiểm tra trùng email / sdt với tài khoản khác
        $sqlCheck = "SELECT ma_kh 
                     FROM khach_hang 
                     WHERE (email = ? OR sdt = ?)
                       AND ma_kh <> ?
                     LIMIT 1";
        $stmtCheck = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ssi", $email, $sdt, $userId);
        mysqli_stmt_execute($stmtCheck);
        $rsCheck = mysqli_stmt_get_result($stmtCheck);

        if (mysqli_fetch_assoc($rsCheck)) {
            $error = "Email hoặc số điện thoại đã được sử dụng bởi tài khoản khác.";
        } else {
            // Cập nhật DB
            $sqlUpdate = "UPDATE khach_hang
                          SET ho_ten = ?, email = ?, sdt = ?
                          WHERE ma_kh = ?";
            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "sssi", $ho_ten, $email, $sdt, $userId);

            if (mysqli_stmt_execute($stmtUpdate)) {
                $success = "Cập nhật hồ sơ thành công!";

                // Cập nhật lại session
                $_SESSION['user']['HoTen'] = $ho_ten;
                $_SESSION['user']['Email'] = $email;
                $_SESSION['user']['SDT']   = $sdt;

                // Cập nhật lại biến $user để form hiển thị giá trị mới
                $user['ho_ten'] = $ho_ten;
                $user['email']  = $email;
                $user['sdt']    = $sdt;
            } else {
                $error = "Có lỗi xảy ra khi cập nhật. Vui lòng thử lại sau.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa hồ sơ của tôi | COZA STORE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap (nếu bạn đã có vendor/bootstrap thì dùng) -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">

    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .profile-wrapper {
            max-width: 900px;
            margin: 40px auto;
        }
        .profile-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px 28px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        .page-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .form-label {
            font-weight: 500;
            font-size: 14px;
        }
        .btn-primary {
            background: #7c4dff;
            border-color: #7c4dff;
        }
        .btn-primary:hover {
            background: #673ab7;
            border-color: #673ab7;
        }
    </style>
</head>
<body>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="page-title">Sửa hồ sơ của tôi</div>
            <div>
                <a href="profile.php" class="btn btn-outline-secondary btn-sm">Quay lại hồ sơ</a>
                <a href="index.php" class="btn btn-outline-dark btn-sm">Trang chủ</a>
            </div>
        </div>

        <?php if ($error !== ""): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <div class="alert alert-success py-2"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label" for="ho_ten">Họ tên</label>
                <input type="text"
                       class="form-control"
                       id="ho_ten"
                       name="ho_ten"
                       value="<?php echo htmlspecialchars($user['ho_ten']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="sdt">Số điện thoại</label>
                <input type="text"
                       class="form-control"
                       id="sdt"
                       name="sdt"
                       value="<?php echo htmlspecialchars($user['sdt']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                Lưu thay đổi
            </button>
        </form>
    </div>
</div>

</body>
</html>
