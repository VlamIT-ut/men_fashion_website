<?php
// shoping-cart.php
session_start();
include 'db.php';

// ====== XỬ LÝ HÀNH ĐỘNG (REMOVE / UPDATE QTY) TRƯỚC KHI TÍNH TOÁN ======

// Xóa sản phẩm (khi bấm nút X)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    // reload lại trang để mini cart + header cập nhật
    header('Location: shoping-cart.php');
    exit;
}

// Cập nhật số lượng (khi bấm + / - hoặc nút "Cập nhật giỏ hàng")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = (int)$qty;
        if ($qty < 1) {
            // nếu nhập 0 hoặc âm thì coi như xóa
            unset($_SESSION['cart'][$id]);
        } else {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] = $qty;
            }
        }
    }
    // reload lại để mini cart cập nhật
    header('Location: shoping-cart.php');
    exit;
}

// ====== Lấy giỏ hàng từ session để hiển thị ======
$cart = $_SESSION['cart'] ?? [];

// Đếm số item trong giỏ (tính theo qty cho badge)
$cartCount = 0;
$subtotal  = 0;
foreach ($cart as $item) {
    $qty       = (int)($item['qty'] ?? 1);
    $cartCount += $qty;
    $subtotal  += $item['gia'] * $qty;
}

// Wishlist cho icon header
$wishlist  = $_SESSION['wishlist'] ?? [];
$wishCount = count($wishlist);

// Hàm lấy tên hiển thị tài khoản
function getDisplayName($sessionValue, $default = 'Tài khoản') {
    if (is_array($sessionValue)) {
        if (!empty($sessionValue['username'])) return $sessionValue['username'];
        if (!empty($sessionValue['HoTen']))    return $sessionValue['HoTen'];
    } elseif (!empty($sessionValue)) {
        return $sessionValue;
    }
    return $default;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Giỏ hàng</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->	
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->	
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>

<body class="animsition">

<!-- ================= HEADER (giống các trang khác của bạn) ================= -->
<header class="header-v4">
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar">
                    Miễn phí vận chuyển cho đơn hàng trên 2.000.000₫
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        Trợ giúp & FAQs
                    </a>

                   <?php if (isset($_SESSION['user'])): 
   $displayName = getDisplayName($_SESSION['user'], 'Thành viên');
   $firstChar   = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
?>
    <!-- MINI PROFILE DROPDOWN DESKTOP -->
    <div class="header-user-dropdown flex-c-m p-lr-25">
        <div class="user-trigger js-user-trigger">
            <div class="user-avatar">
                <?php echo htmlspecialchars($firstChar); ?>
            </div>
            <div class="user-info">
                <span class="user-name">
                    <?php echo htmlspecialchars($displayName); ?>
                </span>
                <span class="user-role">
                    Thành viên
                </span>
            </div>
            <i class="zmdi zmdi-chevron-down user-chevron"></i>
        </div>

        <div class="user-menu">
            <a href="profile.php" class="user-menu-item">
                Hồ sơ của tôi
            </a>
            <a href="orders.php" class="user-menu-item">
                Lịch sử giao dịch
            </a>
            <a href="logout.php" class="user-menu-item user-logout">
                Đăng xuất
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- Chưa đăng nhập -->
    <a href="login.php" class="flex-c-m trans-04 p-lr-25">
        Đăng nhập
    </a>
<?php endif; ?>


                    <a href="#" class="flex-c-m trans-04 p-lr-25">VI</a>
                    <a href="#" class="flex-c-m trans-04 p-lr-25">VND</a>
                </div>
            </div>
        </div>

        <div class="wrap-menu-desktop how-shadow1">
            <nav class="limiter-menu-desktop container">
                <!-- Logo desktop -->		
                <a href="index.php" class="logo">
                    <img src="images/icons/logo-01.png" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="product.php">Sản phẩm</a></li>
                        <li class="label1" data-label1="hot"><a href="shoping-cart.php">Giỏ hàng</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="about.php">Giới thiệu</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m">
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search"></i>
                    </div>

                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                         data-notify="<?php echo $cartCount; ?>">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </div>

                    <a href="javascript:void(0);"
                       class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-wishlist"
                       data-notify="<?php echo $wishCount; ?>">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
        <div class="logo-mobile">
            <a href="index.php"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
        </div>

        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                <i class="zmdi zmdi-search"></i>
            </div>

            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
                 data-notify="<?php echo $cartCount; ?>">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>

            <a href="javascript:void(0);"
               class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-wishlist"
               data-notify="<?php echo $wishCount; ?>">
                <i class="zmdi zmdi-favorite-outline"></i>
            </a>
        </div>

        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </div>
    </div>

    <!-- Menu mobile -->
    <div class="menu-mobile">
        <ul class="topbar-mobile">
            <li>
                <div class="left-top-bar">
                    Miễn phí vận chuyển cho đơn hàng trên 2.000.000₫
                </div>
            </li>

            <li>
                <div class="right-top-bar flex-w h-full">
                    <a href="#" class="flex-c-m p-lr-10 trans-04">Trợ giúp & FAQs</a>
                   <?php if (isset($_SESSION['user'])): 
   $displayName = getDisplayName($_SESSION['user'], 'Thành viên');
   $firstChar   = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
?>
    <!-- MINI PROFILE DROPDOWN DESKTOP -->
    <div class="header-user-dropdown flex-c-m p-lr-25">
        <div class="user-trigger js-user-trigger">
            <div class="user-avatar">
                <?php echo htmlspecialchars($firstChar); ?>
            </div>
            <div class="user-info">
                <span class="user-name">
                    <?php echo htmlspecialchars($displayName); ?>
                </span>
                <span class="user-role">
                    Thành viên
                </span>
            </div>
            <i class="zmdi zmdi-chevron-down user-chevron"></i>
        </div>

        <div class="user-menu">
            <a href="profile.php" class="user-menu-item">
                Hồ sơ của tôi
            </a>
            <a href="orders.php" class="user-menu-item">
                Lịch sử giao dịch
            </a>
            <a href="logout.php" class="user-menu-item user-logout">
                Đăng xuất
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- Chưa đăng nhập -->
    <a href="login.php" class="flex-c-m trans-04 p-lr-25">
        Đăng nhập
    </a>
<?php endif; ?>

                    <a href="#" class="flex-c-m p-lr-10 trans-04">VI</a>
                    <a href="#" class="flex-c-m p-lr-10 trans-04">VND</a>
                </div>
            </li>
        </ul>

        <ul class="main-menu-m">
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="product.php">Sản phẩm</a></li>
            <li><a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Giỏ hàng</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="about.php">Giới thiệu</a></li>
            <li><a href="contact.php">Liên hệ</a></li>
        </ul>
    </div>

    <!-- Modal Search -->
    <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
        <div class="container-search-header">
            <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                <img src="images/icons/icon-close2.png" alt="CLOSE">
            </button>

            <form class="wrap-search-header flex-w p-l-15">
                <button class="flex-c-m trans-04">
                    <i class="zmdi zmdi-search"></i>
                </button>
                <input class="plh3" type="text" name="search" placeholder="Tìm kiếm...">
            </form>
        </div>
    </div>
</header>

<!-- Mini cart & wishlist -->
<div id="mini-cart-container">
    <?php include 'mini_cart.php'; ?>
</div>
<div id="mini-wishlist-container">
    <?php include 'mini_wishlist.php'; ?>
</div>
<?php include 'search_modal.php'; ?>

<!-- ================= BREADCRUMB ================= -->
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
            Trang chủ
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">Giỏ hàng</span>
    </div>
</div>

<!-- ================= SHOPPING CART TABLE ================= -->
<form id="cart-form" class="bg0 p-t-75 p-b-85" method="post" action="shoping-cart.php">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">Sản phẩm</th>
                                <th class="column-2"></th>
                                <th class="column-3">Đơn giá</th>
                                <th class="column-4">Số lượng</th>
                                <th class="column-5">Tổng</th>
                            </tr>

                            <?php if (empty($cart)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-t-40 p-b-40">
                                        Giỏ hàng đang trống.
                                        <a href="product.php" class="cl1">Tiếp tục mua sắm</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cart as $id => $item): ?>
                                    <?php
                                        $qty   = (int)($item['qty'] ?? 1);
                                        $total = $item['gia'] * $qty;
                                    ?>
                                    <tr class="table_row">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <img src="images/<?php echo htmlspecialchars($item['hinh']); ?>" alt="IMG">
                                            </div>
                                        </td>
                                        <td class="column-2">
                                            <?php echo htmlspecialchars($item['ten_sp']); ?>
                                        </td>
                                        <td class="column-3">
                                            <?php echo number_format($item['gia'], 0, ',', '.'); ?>₫
                                        </td>
                                      <td class="column-4">
    <div class="wrap-num-product flex-w m-l-auto m-r-0">
        <button type="button"
                class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"
                data-id="<?php echo $id; ?>">
            <i class="fs-16 zmdi zmdi-minus"></i>
        </button>

        <input class="mtext-104 cl3 txt-center num-product"
               type="number"
               name="qty[<?php echo $id; ?>]"
               value="<?php echo $qty; ?>"
               min="1">

        <button type="button"
                class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"
                data-id="<?php echo $id; ?>">
            <i class="fs-16 zmdi zmdi-plus"></i>
        </button>
    </div>
</td>
<td class="column-5">
    <?php echo number_format($total, 0, ',', '.'); ?>₫
    &nbsp;
    <a href="shoping-cart.php?delete=<?php echo $id; ?>" class="cl1">X</a>
</td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                        <div class="flex-w flex-m m-r-20 m-tb-5">
                            <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5"
                                   type="text" name="coupon" placeholder="Mã giảm giá">
                            <div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                                Áp dụng
                            </div>
                        </div>

                      <button type="submit"
        class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
    Cập nhật giỏ hàng
</button>

                    </div>
                </div>
            </div>

            <!-- Tổng tiền -->
            <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30">
                        Tổng giỏ hàng
                    </h4>

                    <div class="flex-w flex-t bor12 p-b-13">
                        <div class="size-208">
                            <span class="stext-110 cl2">Tạm tính:</span>
                        </div>
                        <div class="size-209">
                            <span class="mtext-110 cl2">
                                <?php echo number_format($subtotal, 0, ',', '.'); ?>₫
                            </span>
                        </div>
                    </div>

                    <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                        <div class="size-208 w-full-ssm">
                            <span class="stext-110 cl2">Vận chuyển:</span>
                        </div>

                        <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                            <p class="stext-111 cl6 p-t-2">
                                Vui lòng nhập địa chỉ giao hàng để tính phí vận chuyển.
                            </p>

                            <div class="p-t-15">
                                <span class="stext-112 cl8">Tính phí vận chuyển</span>

                                <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9">
                                   <select class="js-select2" name="province">
    <option value="">Chọn tỉnh/thành...</option>
<option value="Hà Nội">Hà Nội</option>
<option value="Huế">Huế</option>
<option value="Lai Châu">Lai Châu</option>
<option value="Điện Biên">Điện Biên</option>
<option value="Sơn La">Sơn La</option>
<option value="Lạng Sơn">Lạng Sơn</option>
<option value="Quảng Ninh">Quảng Ninh</option>
<option value="Thanh Hóa">Thanh Hóa</option>
<option value="Nghệ An">Nghệ An</option>
<option value="Hà Tĩnh">Hà Tĩnh</option>
<option value="Cao Bằng">Cao Bằng</option>
<option value="Tuyên Quang">Tuyên Quang</option>
<option value="Lào Cai">Lào Cai</option>
<option value="Thái Nguyên">Thái Nguyên</option>
<option value="Phú Thọ">Phú Thọ</option>
<option value="Bắc Ninh">Bắc Ninh</option>
<option value="Hưng Yên">Hưng Yên</option>
<option value="TP Hải Phòng">TP Hải Phòng</option>
<option value="Ninh Bình">Ninh Bình</option>
<option value="Quảng Trị">Quảng Trị</option>
<option value="TP Đà Nẵng">TP Đà Nẵng</option>
<option value="Quảng Ngãi">Quảng Ngãi</option>
<option value="Gia Lai">Gia Lai</option>
<option value="Khánh Hòa">Khánh Hòa</option>
<option value="Lâm Đồng">Lâm Đồng</option>
<option value="Đắk Lắk">Đắk Lắk</option>
<option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
<option value="Đồng Nai">Đồng Nai</option>
<option value="Tây Ninh">Tây Ninh</option>
<option value="TP Cần Thơ">TP Cần Thơ</option>
<option value="Vĩnh Long">Vĩnh Long</option>
<option value="Đồng Tháp">Đồng Tháp</option>
<option value="An Giang">An Giang</option>
<option value="Cà Mau">Cà Mau</option>

</select>
                                    <div class="dropDownSelect2"></div>
                                </div>

                                <div class="bor8 bg0 m-b-12">
                                    <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="state" placeholder="Quận/Huyện">
                                </div>

                                <div class="bor8 bg0 m-b-22">
                                    <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="postcode" placeholder="Mã bưu điện">
                                </div>

                                <div class="flex-w">
                                    <div class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                                        Cập nhật
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-w flex-t p-t-27 p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">Tổng cộng:</span>
                        </div>
                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2">
                                <?php echo number_format($subtotal, 0, ',', '.'); ?>₫
                            </span>
                        </div>
                    </div>

                    <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                        Tiến hành thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ================= FOOTER (giữ như template nhóm) ================= -->
<footer class="bg3 p-t-75 p-b-32">
    <div class="container">
        <div class="row">
            <!-- giữ nguyên 4 cột footer như bạn đang có -->
            ...
        </div>
    </div>
</footer>

<div class="btn-back-to-top" id="myBtn">
    <span class="symbol-btn-back-to-top">
        <i class="zmdi zmdi-chevron-up"></i>
    </span>
</div>

<!-- JS -->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/animsition/js/animsition.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script>
    $(".js-select2").each(function(){
        $(this).select2({
            minimumResultsForSearch: 20,
            dropdownParent: $(this).next('.dropDownSelect2')
        });
    })
</script>
<script>
    // Chỉ nghe click + / - rồi submit form,
    // còn việc cộng / trừ 1 đơn vị để main.js của template xử lý
    $(document).on('click', '.btn-num-product-down, .btn-num-product-up', function (e) {
        // Đợi 1 chút cho script của template update xong value rồi mới submit
        setTimeout(function () {
            $('#cart-form').submit();
        }, 150);
    });
</script>

<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
    $('.js-pscroll').each(function(){
        $(this).css('position','relative');
        $(this).css('overflow','hidden');
        var ps = new PerfectScrollbar(this, {
            wheelSpeed: 1,
            scrollingThreshold: 1000,
            wheelPropagation: false,
        });
        $(window).on('resize', function(){ ps.update(); })
    });
</script>
<script src="js/main.js"></script>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>

<script>
// CHỈ DÙNG: mở/đóng mini favourite + xoá item trong mini favourite

$(function(){

    // ===== MỞ MINI FAVOURITE (PANEL BÊN PHẢI) =====
    $(document).on('click', '.js-show-wishlist', function(e){
        e.preventDefault();
        $('.js-panel-wishlist').addClass('show-header-cart');
    });

    // ===== ĐÓNG MINI FAVOURITE =====
    $(document).on('click', '.js-hide-wishlist', function(e){
        e.preventDefault();
        $('.js-panel-wishlist').removeClass('show-header-cart');
    });

    // ===== XOÁ 1 SẢN PHẨM TRONG MINI FAVOURITE (AJAX) =====
    $(document).on('click', '.js-remove-wish', function(e){
        e.preventDefault();

        var id = $(this).data('id');   // lấy id sản phẩm từ data-id

        $.post('wishlist_action.php', {
            id: id,
            action: 'remove',
            ajax: 1
        }, function(res){
            // wishlist_action.php cần trả JSON: {status, mini_wishlist_html, wish_count}
            if (res.status === 'success') {
                // Cập nhật lại HTML mini wishlist
                $('#mini-wishlist-container').html(res.mini_wishlist_html);

                // Cập nhật badge số tim trên icon header
                $('.js-show-wishlist').attr('data-notify', res.wish_count);
            } else {
                alert(res.message || 'Có lỗi khi xoá khỏi yêu thích.');
            }
        }, 'json').fail(function(){
            alert('Không gọi được wishlist_action.php');
        });
    });

});
</script>
<script>
    // Toggle mini profile
    $(document).on('click', '.js-user-trigger', function (e) {
        e.stopPropagation();
        var $dropdown = $(this).closest('.header-user-dropdown');
        $('.header-user-dropdown').not($dropdown).removeClass('open');
        $dropdown.toggleClass('open');
    });

    // Click ra ngoài thì đóng
    $(document).on('click', function () {
        $('.header-user-dropdown').removeClass('open');
    });
</script>
</body>
</html>
