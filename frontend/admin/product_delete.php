<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit;
}

include "../db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Lấy và xóa ảnh từ disk
    $sqlImgList = "SELECT ten_anh FROM hinhanh_sp WHERE ma_sp = ?";
    $stmtImgList = mysqli_prepare($conn, $sqlImgList);
    if ($stmtImgList) {
        mysqli_stmt_bind_param($stmtImgList, "i", $id);
        mysqli_stmt_execute($stmtImgList);
        $result = mysqli_stmt_get_result($stmtImgList);
        while ($row = mysqli_fetch_assoc($result)) {
            $path1 = "../images/products/" . $row['ten_anh'];
            $path2 = "../frontend/images/" . $row['ten_anh'];
            if (file_exists($path1)) @unlink($path1);
            if (file_exists($path2)) @unlink($path2);
        }
    }

    // Xóa ảnh trong DB
    $sqlDelImg = "DELETE FROM hinhanh_sp WHERE ma_sp = ?";
    $stmtDelImg = mysqli_prepare($conn, $sqlDelImg);
    if ($stmtDelImg) {
        mysqli_stmt_bind_param($stmtDelImg, "i", $id);
        mysqli_stmt_execute($stmtDelImg);
    }

    // Xóa sản phẩm
    $sqlDelProduct = "DELETE FROM san_pham WHERE ma_sp = ?";
    $stmtDelProduct = mysqli_prepare($conn, $sqlDelProduct);
    if ($stmtDelProduct) {
        mysqli_stmt_bind_param($stmtDelProduct, "i", $id);
        mysqli_stmt_execute($stmtDelProduct);
    }
}

header("Location: product_list.php");
exit;
