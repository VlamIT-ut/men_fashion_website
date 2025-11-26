<?php
session_start();
include "db.php";

$error = "";

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["btnUserLogin"])) {
    $input = trim($_POST["username"]);
    $pass  = trim($_POST["password"]);

    if ($input == "" || $pass == "") {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        $sql = "SELECT * FROM khach_hang 
                WHERE email = ? OR sdt = ?
                AND trang_thai = 1
                LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $input, $input);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {

            // Nếu sau này dùng md5: if (md5($pass) === $row["matkhau"])
                if ((int)$row["trang_thai"] === 0) {
                $error = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
            } else {
                // 2. Kiểm tra mật khẩu (đang dùng md5)
                if (md5($pass) === $row["matkhau"]) {

                    $_SESSION["user"] = [
                        "id"    => $row["ma_kh"],
                        "HoTen" => $row["ho_ten"],
                        "Email" => $row["email"],
                        "SDT"   => $row["sdt"]
                    ];

                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Sai mật khẩu!";
                }
            }

        } else {
            $error = "Email hoặc số điện thoại không tồn tại!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập | Fashion Store</title>
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
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            border-radius: 999px;
            border: 1px solid #ddd;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fafafa;
        }
        input[type="text"]:focus, input[type="password"]:focus {
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
        .helper-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-top: 8px;
        }
        .helper-row a { color: #7c4dff; text-decoration: none; }
        .helper-row a:hover { text-decoration: underline; }
        .error-msg {
            margin-top: 12px;
            font-size: 13px;
            color: #d32f2f;
            background-color: #ffebee;
            border-radius: 10px;
            padding: 8px 10px;
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
            <div class="brand-subtitle">Đăng nhập để tiếp tục mua sắm</div>
        </div>

        <form action="" method="POST">
            <h2>Đăng nhập</h2>

            <div class="form-group">
                <label for="username">Email hoặc Số điện thoại</label>
                <input type="text" id="username" name="username" placeholder="Nhập email hoặc số điện thoại">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
            </div>

            <button type="submit" name="btnUserLogin" class="btn-submit">Đăng nhập</button>

            <div class="helper-row">
                <span></span>
                <a href="forgot_password.php">Quên mật khẩu?</a>
            </div>

            <?php if ($error != ""): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="signup-text">
                Chưa có tài khoản?
                <a href="register.php">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
