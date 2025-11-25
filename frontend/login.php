<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST["btnUserLogin"])) {
    // Lấy dữ liệu từ form (DÙNG EMAIL)
    $email = trim($_POST["username"]);
    $pass  = trim($_POST["password"]);

    if ($email == "" || $pass == "") {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu!";
    } else {

        $sql = "SELECT * FROM khach_hang WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {

            if ($pass === $row["matkhau"]) {

                $_SESSION["user"] = [
                    "id"    => $row["ma_kh"],
                    "HoTen" => $row["ho_ten"],
                    "Email" => $row["email"]
                ];

                header("Location: index.php");
                exit;
            } else {
                $error = "Sai email hoặc mật khẩu!";
            }
        } else {
            $error = "Sai email hoặc mật khẩu!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập người dùng</title>
</head>
<body>
<h2>Đăng nhập người dùng</h2>

<form action="" method="POST">
    <input type="text" name="username" placeholder="Email">
    <input type="password" name="password" placeholder="Mật khẩu">
    <button type="submit" name="btnUserLogin">Đăng nhập</button>
</form>

<?php if ($error != "") echo "<p style='color:red;'>$error</p>"; ?>

</body>
</html>
