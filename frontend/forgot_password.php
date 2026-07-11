<?php
session_start();
include "db.php";

$error = "";
$success = "";
$step = 1; // Step 1: Verify email and phone number, Step 2: Input new password

// If already logged in, redirect to index
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

// Handle Step 1: Verification
if (isset($_POST["btnVerify"]) && $step == 1) {
    $email = trim($_POST["email"]);
    $sdt = trim($_POST["sdt"]);

    if ($email == "" || $sdt == "") {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        $sql = "SELECT ma_kh, ho_ten FROM khach_hang WHERE email = ? AND sdt = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $sdt);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION["reset_user_id"] = $row["ma_kh"];
            $step = 2;
        } else {
            $error = "Email hoặc số điện thoại không trùng khớp với tài khoản nào!";
        }
    }
}

// Handle Step 2: Password Reset
if (isset($_POST["btnReset"]) && isset($_SESSION["reset_user_id"])) {
    $step = 2; // Stay on step 2 if we are submitting
    $pass = trim($_POST["password"]);
    $repass = trim($_POST["repassword"]);
    $user_id = $_SESSION["reset_user_id"];

    if ($pass == "" || $repass == "") {
        $error = "Vui lòng nhập đầy đủ mật khẩu!";
    } elseif (strlen($pass) < 6) {
        $error = "Mật khẩu phải từ 6 ký tự trở lên.";
    } elseif ($pass !== $repass) {
        $error = "Xác nhận mật khẩu không trùng khớp.";
    } else {
        $hashed_pass = md5($pass);
        $sql = "UPDATE khach_hang SET matkhau = ? WHERE ma_kh = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $hashed_pass, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.";
            unset($_SESSION["reset_user_id"]);
            $step = 3; // Step 3: Success state
        } else {
            $error = "Có lỗi xảy ra trong quá trình cập nhật. Vui lòng thử lại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu | Fashion Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fce4ec, #e3f2fd);
        }
        .auth-wrapper { width: 100%; max-width: 420px; padding: 20px; }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px 28px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .auth-card::before {
            content: "";
            position: absolute;
            right: -40px;
            top: -40px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle at center, #ff80ab, transparent 60%);
            opacity: 0.5;
            z-index: 0;
        }
        .brand {
            text-align: center;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }
        .brand-logo {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff80ab, #7c4dff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 22px;
            margin: 0 auto 10px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        }
        .brand-title { font-size: 20px; font-weight: 600; letter-spacing: 1px; }
        .brand-subtitle { font-size: 13px; color: #777; margin-top: 4px; }

        h2 { font-size: 18px; margin-bottom: 16px; font-weight: 500; color: #333; }
        form { position: relative; z-index: 1; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; margin-bottom: 6px; color: #555; }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 11px 12px;
            border-radius: 999px;
            border: 1px solid #ddd;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fafafa;
        }
        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
            border-color: #ff80ab;
            box-shadow: 0 0 0 3px rgba(255,128,171,0.2);
            background-color: #fff;
        }
        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 11px 0;
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            background: linear-gradient(135deg, #ff80ab, #7c4dff);
            color: #fff;
            margin-top: 4px;
            transition: transform 0.1s ease-out, box-shadow 0.15s ease-out, filter 0.1s;
            box-shadow: 0 10px 20px rgba(124,77,255,0.35);
        }
        .btn-submit:hover {
            filter: brightness(1.05);
            box-shadow: 0 12px 26px rgba(124,77,255,0.45);
        }
        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 6px 14px rgba(124,77,255,0.35);
        }
        .error-msg {
            margin-top: 12px;
            font-size: 13px;
            color: #d32f2f;
            background-color: #ffebee;
            border-radius: 10px;
            padding: 8px 10px;
        }
        .success-msg {
            margin-top: 12px;
            font-size: 13px;
            color: #2e7d32;
            background-color: #e8f5e9;
            border-radius: 10px;
            padding: 8px 10px;
            text-align: center;
        }
        .signup-text {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
            color: #555;
        }
        .signup-text a {
            color: #ff4081;
            text-decoration: none;
            font-weight: 500;
        }
        .signup-text a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            .auth-card { padding: 24px 18px 22px; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-logo">CS</div>
            <div class="brand-title">COZA STORE</div>
            <div class="brand-subtitle">Đặt lại mật khẩu tài khoản của bạn</div>
        </div>

        <?php if ($step == 1): ?>
            <form action="" method="POST">
                <h2>Xác minh tài khoản</h2>

                <div class="form-group">
                    <label for="email">Email đăng ký</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
                </div>

                <div class="form-group">
                    <label for="sdt">Số điện thoại đăng ký</label>
                    <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại của bạn" required>
                </div>

                <button type="submit" name="btnVerify" class="btn-submit">Xác nhận</button>

                <?php if ($error != ""): ?>
                    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="signup-text">
                    Quay lại <a href="login.php">Đăng nhập</a>
                </div>
            </form>
        <?php elseif ($step == 2): ?>
            <form action="" method="POST">
                <h2>Mật khẩu mới</h2>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới" required>
                </div>

                <div class="form-group">
                    <label for="repassword">Xác nhận mật khẩu</label>
                    <input type="password" id="repassword" name="repassword" placeholder="Nhập lại mật khẩu mới" required>
                </div>

                <button type="submit" name="btnReset" class="btn-submit">Cập nhật mật khẩu</button>

                <?php if ($error != ""): ?>
                    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
            </form>
        <?php elseif ($step == 3): ?>
            <div class="success-msg"><?php echo $success; ?></div>
            <div class="signup-text" style="margin-top: 24px;">
                <a href="login.php" class="btn-submit" style="display: block; text-align: center; text-decoration: none; line-height: 24px;">Đăng nhập ngay</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
