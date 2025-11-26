<?php
session_start();
include 'db.php';

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
	<title>Giới thiệu</title>
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

							<li class="active-menu">
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

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"  <?php
$cart = $_SESSION['cart'] ?? [];
$badge = 0;
foreach ($cart as $it) {
    $badge += (int)($it['qty'] ?? 1);
}
?>
<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
     data-notify="<?php echo $badge; ?>">
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

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"  <?php
$cart = $_SESSION['cart'] ?? [];
$badge = 0;
foreach ($cart as $it) {
    $badge += (int)($it['qty'] ?? 1);
}
?>
<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
     data-notify="<?php echo $badge; ?>">
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


	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/bg-01.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
			Giới thiệu
		</h2>
	</section>	


	<!-- Content page -->
	<section class="bg0 p-t-75 p-b-120">
		<div class="container">
			<div class="row p-b-148">
				<div class="col-md-7 col-lg-8">
					<div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							Câu chuyện của chúng tôi
						</h3>

						<p class="stext-113 cl6 p-b-26">
							Thành lập vào năm 2023, Men's Elegance tự hào là điểm đến thời trang cao cấp dành cho quý ông. Với sứ mệnh nâng tầm phong cách thời trang nam giới Việt Nam, chúng tôi mang đến những bộ sưu tập từ trang phục công sở lịch lãm đến casual năng động, đáp ứng mọi nhu cầu của khách hàng.
						</p>

						<p class="stext-113 cl6 p-b-26">
							Tại Men's Elegance, mỗi sản phẩm đều được chọn lọc kỹ càng từ các thương hiệu uy tín, với chất liệu cao cấp và kiểu dáng thời thượng. Chúng tôi không chỉ bán quần áo mà còn mang đến giải pháp phong cách toàn diện cho quý ông hiện đại, từ trang phục đến phụ kiện thời trang.
						</p>

						<p class="stext-113 cl6 p-b-26">
							Mọi thắc mắc xin liên hệ với chúng tôi tại cửa hàng ở tầng 8, 379 Đường Hudson, New York, NY 10018 hoặc gọi điện theo số (+1) 96 716 6879
						</p>
					</div>
				</div>

				<div class="col-11 col-md-5 col-lg-4 m-lr-auto">
					<div class="how-bor1 ">
						<div class="hov-img0">
							<img src="images/about-01.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="order-md-2 col-md-7 col-lg-8 p-b-30">
					<div class="p-t-7 p-l-85 p-l-15-lg p-l-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							Sứ mệnh của chúng tôi
						</h3>

						<p class="stext-113 cl6 p-b-26">
							Chúng tôi tin rằng "Phong cách là cách người đàn ông thể hiện bản thân". Với đội ngũ tư vấn viên chuyên nghiệp và am hiểu thời trang, chúng tôi không chỉ bán sản phẩm mà còn đồng hành cùng quý ông tạo nên phong cách riêng. Mục tiêu của chúng tôi là trở thành thương hiệu thời trang nam cao cấp hàng đầu, nơi mà mỗi quý ông đều có thể tìm thấy phiên bản hoàn hảo nhất của chính mình.
						</p>

						<div class="bor16 p-l-29 p-b-9 m-t-22">
							<p class="stext-114 cl6 p-r-40 p-b-11">
								Sáng tạo chỉ đơn giản là kết nối mọi thứ. Khi bạn hỏi những người sáng tạo họ đã làm điều đó như thế nào, họ cảm thấy hơi tội lỗi vì thực sự họ đã không làm gì cả, họ chỉ nhìn thấy điều gì đó. Nó dường như hiển nhiên với họ sau một thời gian.
							</p>

							<span class="stext-111 cl8">
								- Steve Jobs
							</span>
						</div>
					</div>
				</div>

				<div class="order-md-1 col-11 col-md-5 col-lg-4 m-lr-auto p-b-30">
					<div class="how-bor2">
						<div class="hov-img0">
							<img src="images/about-02.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
	
		

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
								Áo polo
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
						Bạn có câu hỏi? Ghé thăm cửa hàng tại Tầng 8, 379 Đường Hudson, New York, NY 10018 hoặc gọi cho chúng tôi theo số (+1) 96 716 6879
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
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> 
All rights reserved | This template is made with 
<i class="fa fa-heart-o" aria-hidden="true"></i>
 by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

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