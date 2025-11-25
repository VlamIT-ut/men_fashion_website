<?php
session_start();
include 'db.php';

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
$cartCount = count($cart);

// Tính tổng tiền
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['gia'] * $item['qty'];
}


// Hàm lấy tên hiển thị
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
	
	<!-- Header -->
	<header class="header-v4">
		<!-- Header desktop -->
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
						<!-- Đã đăng nhập: hiện avatar + tên + Đăng xuất -->
						<div class="flex-c-m trans-04 p-lr-25">
							<div style="display:flex;align-items:center;gap:8px;">
								<div style="
									width:32px;height:32px;border-radius:50%;
									background:#555;color:#fff;
									display:flex;align-items:center;justify-content:center;
									font-weight:bold;font-size:14px;
								">
									<?php echo htmlspecialchars($firstChar); ?>
								</div>
								<div style="display:flex;flex-direction:column;line-height:1.2;">
									<span style="font-size:13px;">
										<?php echo htmlspecialchars($displayName); ?>
									</span>
									<span style="font-size:11px;color:#ccc;">
										Thành viên
									</span>
								</div>
							</div>
						</div>

						<a href="logout.php" class="flex-c-m trans-04 p-lr-25">
							Đăng xuất
						</a>

					<?php else: ?>
						<!-- Chưa đăng nhập -->
						<a href="login.php" class="flex-c-m trans-04 p-lr-25">
							Đăng nhập
						</a>
					<?php endif; ?>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							VI
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							VND
						</a>
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
							<li>
								<a href="index.php">Trang chủ</a>
							</li>

							<li>
								<a href="product.php">Sản phẩm</a>
							</li>

							<li class="label1" data-label1="hot">
								<a href="shoping-cart.php">Giỏ hàng</a>
							</li>

							<li>
								<a href="blog.php">Blog</a>
							</li>

							<li>
								<a href="about.php">Giới thiệu</a>
							</li>

							<li>
								<a href="contact.php">Liên hệ</a>
							</li>
						</ul>
					</div>	

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"data-notify="<?php echo $cartCount; ?>">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>

						<?php
$wishlist    = $_SESSION['wishlist'] ?? [];
$wishCount   = count($wishlist);
?>
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
			<!-- Logo moblie -->		
			<div class="logo-mobile">
				<a href="index.php"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" data-notify="<?php echo $cartCount; ?>">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<?php
$wishlist    = $_SESSION['wishlist'] ?? [];
$wishCount   = count($wishlist);
?>
<a href="javascript:void(0);"
   class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-wishlist"
   data-notify="<?php echo $wishCount; ?>">
    <i class="zmdi zmdi-favorite-outline"></i>
</a>

			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="topbar-mobile">
				<li>
					<div class="left-top-bar">
						Miễn phí vận chuyển cho đơn hàng trên 2.000.000₫
					</div>
				</li>

				<li>
					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							Trợ giúp & FAQs
						</a>

						<?php if (isset($_SESSION['user'])): 
						$displayName = getDisplayName($_SESSION['user'], 'Thành viên');
						$firstChar   = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
					?>
						<!-- Đã đăng nhập: hiện avatar + tên + Đăng xuất -->
						<div class="flex-c-m trans-04 p-lr-25">
							<div style="display:flex;align-items:center;gap:8px;">
								<div style="
									width:32px;height:32px;border-radius:50%;
									background:#555;color:#fff;
									display:flex;align-items:center;justify-content:center;
									font-weight:bold;font-size:14px;
								">
									<?php echo htmlspecialchars($firstChar); ?>
								</div>
								<div style="display:flex;flex-direction:column;line-height:1.2;">
									<span style="font-size:13px;">
										<?php echo htmlspecialchars($displayName); ?>
									</span>
									<span style="font-size:11px;color:#ccc;">
										Thành viên
									</span>
								</div>
							</div>
						</div>

						<a href="logout.php" class="flex-c-m trans-04 p-lr-25">
							Đăng xuất
						</a>

					<?php else: ?>
						<!-- Chưa đăng nhập -->
						<a href="login.php" class="flex-c-m trans-04 p-lr-25">
							Đăng nhập
						</a>
					<?php endif; ?>
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							VI
						</a>

						<a href="#" class="flex-c-m p-lr-10 trans-04">
							VND
						</a>
					</div>
				</li>
			</ul>

			<ul class="main-menu-m">
				<li>
					<a href="index.php">Trang chủ</a>
				</li>

				<li>
					<a href="product.php">Sản phẩm</a>
				</li>

				<li>
					<a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Giỏ hàng</a>
				</li>

				<li>
					<a href="blog.php">Blog</a>
				</li>

				<li>
					<a href="about.php">Giới thiệu</a>
				</li>

				<li>
					<a href="contact.php">Liên hệ</a>
				</li>
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

<div id="mini-cart-container">
    <?php include 'mini_cart.php'; ?>
</div>
<div id="mini-wishlist-container">
    <?php include 'mini_wishlist.php'; ?>
</div>

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Trang chủ
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Giỏ hàng
			</span>
		</div>
	</div>
		

	<!-- Shoping Cart -->
	<form class="bg0 p-t-75 p-b-85">
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
            Giỏ hàng đang trống. <a href="product.php" class="cl1">Tiếp tục mua sắm</a>
        </td>
    </tr>
<?php else: ?>
    <?php foreach ($cart as $id => $item): ?>
        <tr class="table_row">
            <td class="column-1">
                <div class="how-itemcart1">
                    <img src="images/products/<?php echo htmlspecialchars($item['hinh']); ?>" alt="IMG">
                </div>
            </td>
            <td class="column-2"><?php echo htmlspecialchars($item['ten_sp']); ?></td>
            <td class="column-3"><?php echo number_format($item['gia'], 0, ',', '.'); ?>₫</td>
            <td class="column-4">
                <div class="wrap-num-product flex-w m-l-auto m-r-0">
                    <!-- nếu muốn chỉnh tăng/giảm sau này sẽ thêm form -->
                    <input class="mtext-104 cl3 txt-center num-product"
                           type="number" value="<?php echo $item['qty']; ?>" readonly>
                </div>
            </td>
            <td class="column-5">
                <?php echo number_format($item['gia'] * $item['qty'], 0, ',', '.'); ?>₫
                &nbsp;
                <a href="cart_remove.php?id=<?php echo $id; ?>" class="cl1">X</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
								

							</table>
						</div>

						<div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
							<div class="flex-w flex-m m-r-20 m-tb-5">
								<input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text" name="coupon" placeholder="Mã giảm giá">
									
								<div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
									Áp dụng
								</div>
							</div>

							<div class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
								Cập nhật giỏ hàng
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">
							Tổng giỏ hàng
						</h4>

						<div class="flex-w flex-t bor12 p-b-13">
							<div class="size-208">
								<span class="stext-110 cl2">
									Tạm tính:
								</span>
							</div>

							<div class="size-209">
								<span class="mtext-110 cl2">
    <?php echo number_format($subtotal); ?>₫
</span>

							</div>
						</div>

						<div class="flex-w flex-t bor12 p-t-15 p-b-30">
							<div class="size-208 w-full-ssm">
								<span class="stext-110 cl2">
									Vận chuyển:
								</span>
							</div>

							<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
								<p class="stext-111 cl6 p-t-2">
									Vui lòng nhập địa chỉ giao hàng để tính phí vận chuyển. Liên hệ với chúng tôi nếu bạn cần trợ giúp.
								</p>
								
								<div class="p-t-15">
									<span class="stext-112 cl8">
										Tính phí vận chuyển
									</span>

									<div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9">
										<select class="js-select2" name="time">
											<option>Chọn tỉnh/thành...</option>
											<option>Hà Nội</option>
											<option>TP HCM</option>
											<option>Đà Nẵng</option>
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
								<span class="mtext-101 cl2">
									Tổng cộng:
								</span>
							</div>

							<div class="size-209 p-t-1">
								<span class="mtext-110 cl2">
    <?php echo number_format($subtotal); ?>₫
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
		
	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Danh mục
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Áo sơ mi nam
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Vest & Blazer  
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Quần tây nam
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Phụ kiện nam
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Trợ giúp
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Theo dõi đơn hàng
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Đổi trả
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Vận chuyển
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								FAQs
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Liên hệ
					</h4>

					<p class="stext-107 cl7 size-201">
						Bạn có câu hỏi? Ghé thăm cửa hàng tại Số 1 Võ Văn Ngân, P. Linh Chiểu, TP. Thủ Đức, TP.HCM hoặc gọi cho chúng tôi theo số (+84) 123 456 789.
					</p>

					<div class="p-t-27">
						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-instagram"></i>
						</a>

						<a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
							<i class="fa fa-pinterest-p"></i>
						</a>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Đăng ký nhận tin
					</h4>

					<form>
						<div class="wrap-input1 w-full p-b-4">
							<input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email" placeholder="email@example.com">
							<div class="focus-input1 trans-04"></div>
						</div>

						<div class="p-t-18">
							<button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
								Đăng ký
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="p-t-40">
				<div class="flex-c-m flex-w p-b-18">
					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-01.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-02.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-03.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-04.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-05.png" alt="ICON-PAY">
					</a>
				</div>

				<p class="stext-107 cl6 txt-center">
Bản quyền &copy;<script>document.write(new Date().getFullYear());</script> Đã đăng ký bản quyền | Mẫu này được tạo bởi <i class="fa fa-heart-o" aria-hidden="true"></i> bởi <a href="https://colorlib.com" target="_blank">Colorlib</a>
				</p>
			</div>
		</div>
	</footer>


	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<!--===============================================================================================-->
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

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>
