<?php
session_start();
include 'db.php';

// Nếu chưa đăng nhập thì đá về trang login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userSession = $_SESSION['user'];
$userId      = (int)$userSession['id'];

// Lấy thông tin chi tiết của user từ DB
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

// Hàm hiển thị ngày cho đẹp
function fmtDateTime($str) {
    if (empty($str) || $str == '0000-00-00 00:00:00') return 'Chưa có dữ liệu';
    return date('d/m/Y H:i', strtotime($str));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ của tôi | COZA STORE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bạn có thể dùng luôn Bootstrap của admin / frontend -->
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
        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff80ab, #7c4dff);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 600;
            margin-right: 16px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-name {
            font-size: 20px;
            font-weight: 600;
        }
        .profile-sub {
            font-size: 13px;
            color: #777;
        }
        .profile-info dt {
            font-weight: 600;
            color: #555;
        }
        .profile-info dd {
            margin-bottom: 10px;
        }
        .profile-actions a {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="profile-header">
            <?php
            $name = $user['ho_ten'];
            $firstChar = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
            ?>
            <div class="profile-avatar">
                <?php echo htmlspecialchars($firstChar); ?>
            </div>
            <div>
                <div class="profile-name">
                    <?php echo htmlspecialchars($user['ho_ten']); ?>
                </div>
                <div class="profile-sub">
                    Thành viên COZA STORE
                </div>
            </div>
        </div>

        <hr>

        <dl class="row profile-info">
            <dt class="col-sm-3">Mã khách hàng</dt>
            <dd class="col-sm-9">#<?php echo (int)$user['ma_kh']; ?></dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><?php echo htmlspecialchars($user['email']); ?></dd>

            <dt class="col-sm-3">Số điện thoại</dt>
            <dd class="col-sm-9"><?php echo htmlspecialchars($user['sdt']); ?></dd>

        </dl>

       <div class="profile-actions mt-3">
    <a href="index.php" class="btn btn-secondary btn-sm">Về trang chủ</a>
    <a href="profile_edit.php" class="btn btn-primary btn-sm">Sửa thông tin</a>
    <!-- Sau làm thêm:
    <a href="change_password.php" class="btn btn-outline-primary btn-sm">Đổi mật khẩu</a>
    -->
</div>

    </div>
</div>

</body>
</html>
