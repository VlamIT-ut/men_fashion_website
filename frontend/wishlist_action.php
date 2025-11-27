<?php
// wishlist_action.php
session_start();
include 'db.php';

// Lấy chung từ GET/POST
$id     = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$action = $_REQUEST['action']    ?? 'toggle';
$isAjax = !empty($_REQUEST['ajax']);

if ($id <= 0) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
        exit;
    }
    header('Location: index.php');
    exit;
}

// Nếu chưa có mảng wishlist thì tạo
if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

$wishlist = &$_SESSION['wishlist'];

/*
 * action:
 *   - remove: xoá khỏi wishlist
 *   - toggle (mặc định): đang có thì xoá, chưa có thì thêm
 */

if ($action === 'remove') {
    // Chỉ xoá, không thêm
    unset($wishlist[$id]);
} else {
    // TOGGLE:
    if (isset($wishlist[$id])) {
        // ĐÃ CÓ -> XOÁ
        unset($wishlist[$id]);
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
        }
    }
}

// Nếu là AJAX -> trả JSON cho JS xử lý, KHÔNG redirect
if ($isAjax) {
    ob_start();
    include 'mini_wishlist.php';      // render lại panel mini favourite
    $mini_html = ob_get_clean();

    $wishCount = count($_SESSION['wishlist']);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'             => 'success',
        'mini_wishlist_html' => $mini_html,
        'wish_count'         => $wishCount,
    ]);
    exit;
}

// Nếu KHÔNG phải AJAX -> hành vi cũ: redirect về trang trước
$_SESSION['show_wishlist'] = true; // có thể dùng để auto mở panel nếu muốn
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $back");
exit;
