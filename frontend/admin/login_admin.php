<?php
session_start();
include "../db.php";

$error = "";

if (isset($_POST["btnAdminLogin"])) {
    $user = trim($_POST["username"] ?? "");
    $pass = trim($_POST["password"] ?? "");

    if ($user == "" || $pass == "") {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    } else {
        $pass_md5 = md5($pass);

      $sql = "SELECT * FROM admin WHERE username = ? AND password = ? LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $user, $pass_md5);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Lưu thông tin admin vào session riêng
            $_SESSION["admin"] = [
                "id"       => $row["id"],
                "username" => $row["username"],
                "role"     => $row["role"]
            ];

          

            header("Location: index.php");
            exit;
        } else {
            $error = "Sai tài khoản / mật khẩu hoặc không phải admin!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập quản trị</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: none;
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            padding: 35px 30px 10px;
            text-align: center;
        }
        .card-header h4 {
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .card-header p {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }
        .card-body {
            padding: 20px 30px 35px;
        }
        .form-label {
            font-weight: 500;
            font-size: 13px;
            color: #475569;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            background-color: #fff;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 20px -3px rgba(79, 70, 229, 0.4);
            filter: brightness(1.05);
        }
        .btn-primary:active {
            transform: translateY(1px);
        }
        .admin-icon-container {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            color: #fff;
            font-size: 24px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="card shadow">
        <div class="card-header">
            <div class="admin-icon-container">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h4>ĐĂNG NHẬP QUẢN TRỊ</h4>
            <p>Nhập thông tin quản trị viên để tiếp tục</p>
        </div>
        <div class="card-body">
            <?php if ($error != ""): ?>
                <div class="alert alert-danger py-2" style="font-size: 13px; border-radius: 10px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required
                           value="<?php echo htmlspecialchars($user ?? ""); ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" name="btnAdminLogin"
                        class="btn btn-primary w-100">
                    Đăng nhập hệ thống
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
