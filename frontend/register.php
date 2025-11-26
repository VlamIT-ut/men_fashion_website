<?php
session_start();
include "db.php";

$error   = "";
$success = "";

// Nếu đã đăng nhập rồi thì không cho đăng ký nữa
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["btnRegister"])) {

    $hoten   = trim($_POST["ho_ten"]);
    $email   = trim($_POST["email"]);
    $diachi  = trim($_POST["dia_chi"]);
    $sdt     = trim($_POST["sdt"]);
    $pass    = trim($_POST["password"]);
    $repass  = trim($_POST["repassword"]);

    if ($hoten == "" || $email == "" || $pass == "" || $repass == "") {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc (*).";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    }
    elseif ($pass !== $repass) {
        $error = "Xác nhận mật khẩu không đúng.";
    }
    elseif (strlen($pass) < 6) {
        $error = "Mật khẩu phải từ 6 ký tự trở lên.";
    } else {
        $sql_check = "SELECT ma_kh FROM khach_hang WHERE email = ? LIMIT 1";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_fetch_assoc($result_check)) {
            $error = "Email này đã được sử dụng, vui lòng chọn email khác.";
        } else {

            // Nếu sau này m muốn dùng md5: $pass_save = md5($pass);
            $pass_save = md5($pass);

            $ma_hanmuc = 1;
            $trang_thai = 1;

            $sql_ins = "INSERT INTO khach_hang
                        (ma_hanmuc, ho_ten, dia_chi, sdt, matk hau, email, trang_thai)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            $sql_ins = "INSERT INTO khach_hang
                        (ma_hanmuc, ho_ten, dia_chi, sdt, matkhau, email, trang_thai)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt_ins = mysqli_prepare($conn, $sql_ins);
            mysqli_stmt_bind_param(
                $stmt_ins,
                "isssssi",
                $ma_hanmuc,
                $hoten,
                $diachi,
                $sdt,
                $pass_save,
                $email,
                $trang_thai
            );

            if (mysqli_stmt_execute($stmt_ins)) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
                
            } else {
                $error = "Có lỗi xảy ra khi tạo tài khoản, vui lòng thử lại sau.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký | Fashion Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            font-family:'Poppins',Arial,sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,#fce4ec,#e3f2fd);
        }
        .auth-wrapper{width:100%;max-width:480px;padding:20px;}
        .auth-card{
            background:#fff;
            border-radius:16px;
            padding:28px 26px 24px;
            box-shadow:0 12px 30px rgba(0,0,0,0.08);
            position:relative;
            overflow:hidden;
        }
        .auth-card::before{
            content:"";
            position:absolute;
            right:-40px;top:-40px;
            width:120px;height:120px;
            background:radial-gradient(circle at center,#ff80ab,transparent 60%);
            opacity:.5;
            z-index:0;
        }
        .brand{text-align:center;margin-bottom:18px;position:relative;z-index:1;}
        .brand-logo{
            width:54px;height:54px;border-radius:50%;
            background:linear-gradient(135deg,#ff80ab,#7c4dff);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-weight:600;font-size:22px;
            margin:0 auto 10px;
            box-shadow:0 8px 18px rgba(0,0,0,.15);
        }
        .brand-title{font-size:20px;font-weight:600;letter-spacing:1px;}
        .brand-subtitle{font-size:13px;color:#777;margin-top:4px;}
        h2{font-size:18px;margin-bottom:14px;font-weight:500;color:#333;}
        form{position:relative;z-index:1;}
        .form-group{margin-bottom:12px;}
        label{display:block;font-size:13px;margin-bottom:4px;color:#555;}
        input[type="text"],input[type="password"]{
            width:100%;padding:10px 12px;border-radius:999px;
            border:1px solid #ddd;font-size:14px;outline:none;
            transition:border-color .2s,box-shadow .2s,background-color .2s;
            background-color:#fafafa;
        }
        input[type="text"]:focus,input[type="password"]:focus{
            border-color:#ff80ab;
            box-shadow:0 0 0 3px rgba(255,128,171,.2);
            background-color:#fff;
        }
        .btn-submit{
            width:100%;border:none;border-radius:999px;
            padding:11px 0;font-size:15px;font-weight:500;
            text-transform:uppercase;letter-spacing:.5px;
            cursor:pointer;
            background:linear-gradient(135deg,#ff80ab,#7c4dff);
            color:#fff;margin-top:6px;
            transition:transform .1s ease-out,box-shadow .15s ease-out,filter .1s;
            box-shadow:0 10px 20px rgba(124,77,255,.35);
        }
        .btn-submit:hover{filter:brightness(1.05);box-shadow:0 12px 26px rgba(124,77,255,.45);}
        .btn-submit:active{transform:translateY(1px);box-shadow:0 6px 14px rgba(124,77,255,.35);}
        .error-msg,.success-msg{
            margin-top:10px;font-size:13px;border-radius:10px;
            padding:8px 10px;
        }
        .error-msg{color:#d32f2f;background-color:#ffebee;}
        .success-msg{color:#2e7d32;background-color:#e8f5e9;}
        .signup-text{
            margin-top:16px;text-align:center;font-size:13px;color:#555;
        }
        .signup-text a{color:#ff4081;text-decoration:none;font-weight:500;}
        .signup-text a:hover{text-decoration:underline;}
        @media(max-width:480px){.auth-card{padding:24px 18px 20px;}}
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-logo">CS</div>
            <div class="brand-title">COZA STORE</div>
            <div class="brand-subtitle">Tạo tài khoản để bắt đầu mua sắm</div>
        </div>

        <form method="POST" action="">
            <h2>Đăng ký tài khoản</h2>

            <div class="form-group">
                <label for="ho_ten">Họ tên *</label>
                <input type="text" id="ho_ten" name="ho_ten">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="text" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="dia_chi">Địa chỉ</label>
                <input type="text" id="dia_chi" name="dia_chi">
            </div>

            <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu *</label>
                <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự">
            </div>

            <div class="form-group">
                <label for="repassword">Nhập lại mật khẩu *</label>
                <input type="password" id="repassword" name="repassword" placeholder="Nhập lại mật khẩu">
            </div>

            <button type="submit" name="btnRegister" class="btn-submit">Đăng ký</button>

            <?php if ($error != ""): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success != ""): ?>
                <div class="success-msg"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="signup-text">
                Đã có tài khoản?
                <a href="login.php">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
