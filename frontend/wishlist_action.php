<?php
// wishlist_action.php
session_start();
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Nếu chưa có mảng wishlist thì tạo
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

/*
 * TOGGLE:
 *  - Nếu sản phẩm đã nằm trong wishlist -> xoá (tắt tim)
 *  - Nếu chưa có -> lấy từ DB rồi thêm (bật tim)
 */
if (isset($_SESSION['wishlist'][$id])) {
    // ĐÃ CÓ -> XOÁ
    unset($_SESSION['wishlist'][$id]);
    $_SESSION['show_wishlist'] = true; // vẫn mở panel cho dễ thấy
} else {
    // CHƯA CÓ -> LẤY TỪ DB VÀ THÊM
    $sql = "
        SELECT sp.ma_sp, sp.ten_sp, sp.gia,
               img.ten_anh 
        FROM san_pham sp
        LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp
        WHERE sp.ton_tai = 1 
          AND sp.ma_sp   = $id
        LIMIT 1
    ";

    $rs = mysqli_query($conn, $sql);

    if ($rs && $sp = mysqli_fetch_assoc($rs)) {
        $_SESSION['wishlist'][$id] = [
            'id'      => $sp['ma_sp'],
            'ten_sp'  => $sp['ten_sp'],
            'gia'     => $sp['gia'],
            'ten_anh' => $sp['ten_anh'] ?: 'product-01.jpg'
        ];

        // Đặt cờ để tự mở panel yêu thích
        $_SESSION['show_wishlist'] = true;
    }
}

// Quay lại trang trước
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $back");
exit;
