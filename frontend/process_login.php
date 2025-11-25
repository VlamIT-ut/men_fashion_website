<?php
session_start();
require 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Lưu session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // user / admin

    // Phân quyền
    if ($user['role'] == 'admin') {
        header("Location: adminlogin.php");   // trang admin
        exit();
    } else {
        header("Location: index.php");        // trang user
        exit();
    }
} else {
    echo "Sai tài khoản hoặc mật khẩu!";
}
?>
