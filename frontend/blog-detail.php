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
	<title>Chi tiết bài viết</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
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
								<a href="product.php">Cửa hàng</a>
							</li>

							<li class="label1" data-label1="hot">
								<a href="shoping-cart.php">Tính năng</a>
							</li>

							<li class="active-menu">
								<a href="blog.php">Bài viết</a>
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

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" <?php
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
					<a href="product.php">Cửa hàng</a>
				</li>

				<li>
					<a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Tính năng</a>
				</li>

				<li>
					<a href="blog.php">Bài viết</a>
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

				<form class="wrap-search-header flex-w p-l-15"
      method="get"
      action="product.php">
    <button class="flex-c-m trans-04">
        <i class="zmdi zmdi-search"></i>
    </button>
    <input class="plh3"
           type="text"
           name="q"
           placeholder="Tìm kiếm sản phẩm..."
           value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
</form>

			</div>
		</div>
	</header>




	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Trang chủ
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="blog.php" class="stext-109 cl8 hov-cl1 trans-04">
				Bài viết
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Cách Phối Đồ Nam Đẹp Cho Mùa Đông
			</span>
		</div>
	</div>


	<!-- Content page -->
	<section class="bg0 p-t-52 p-b-20">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-lg-9 p-b-80">
					<div class="p-r-45 p-r-0-lg">
						<!--  -->
						<div class="wrap-pic-w how-pos5-parent">
							<img src="images/blog-04.jpg" alt="IMG-BLOG">

							<div class="flex-col-c-m size-123 bg9 how-pos5">
								<span class="ltext-107 cl2 txt-center">
									22
								</span>

								<span class="stext-109 cl3 txt-center">
									Jan 2018
								</span>
							</div>
						</div>

						<div class="p-t-32">
							<span class="flex-w flex-m stext-111 cl2 p-b-19">
								<span>
									<span class="cl4">Bởi</span> Admin  
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<span>
									22 Tháng 1, 2018
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<span>
									Thời trang đường phố, Thời trang, Cặp đôi  
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<span>
									8 Bình luận
								</span>
							</span>

							<h4 class="ltext-109 cl2 p-b-28">
								Cách Phối Đồ Nam Đẹp Cho Mùa Đông
							</h4>

							<p class="stext-117 cl6 p-b-26">
								Mùa đông là thời điểm tuyệt vời để các quý ông thể hiện phong cách. Từ áo len, áo khoác dạ đến vest layer hợp lý — đây là một vài gợi ý phối đồ nam vừa ấm vừa lịch lãm cho mùa đông.
							</p>

							<p class="stext-117 cl6 p-b-26">
								Mẹo nhanh: chọn áo khoác vừa vặn, kết hợp áo len cổ lọ hoặc áo sơ mi bên trong, sử dụng phụ kiện như khăn, găng tay da và giày boot để hoàn thiện trang phục. Ưu tiên chất liệu dày, màu sắc trung tính và các lớp (layer) hợp lý.
							</p>
						</div>

						<div class="flex-w flex-t p-t-16">
							<span class="size-216 stext-116 cl8 p-t-4">
								Thẻ
							</span>

							<div class="flex-w size-217">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thời trang đường phố
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thủ công
								</a>
							</div>
						</div>

						<!--  -->
						<div class="p-t-40">
							<h5 class="mtext-113 cl2 p-b-12">
								Để lại bình luận
							</h5>

							<p class="stext-107 cl6 p-b-40">
								Email của bạn sẽ không được công khai. Các trường bắt buộc được đánh dấu *
							</p>

							<form>
								<div class="bor19 m-b-20">
									<textarea class="stext-111 cl2 plh3 size-124 p-lr-18 p-tb-15" name="cmt" placeholder="Bình luận..."></textarea>
								</div>

								<div class="bor19 size-218 m-b-20">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="name" placeholder="Tên *">
								</div>

								<div class="bor19 size-218 m-b-20">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="email" placeholder="Email *">
								</div>

								<div class="bor19 size-218 m-b-30">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="web" placeholder="Website">
								</div>

								<button class="flex-c-m stext-101 cl0 size-125 bg3 bor2 hov-btn3 p-lr-15 trans-04">
									Đăng bình luận
								</button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-lg-3 p-b-80">
					<div class="side-menu">
						<div class="bor17 of-hidden pos-relative">
							<input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search" placeholder="Tìm kiếm">

							<button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
								<i class="zmdi zmdi-search"></i>
							</button>
						</div>

						<div class="p-t-55">
							<h4 class="mtext-112 cl2 p-b-33">
								Danh mục
							</h4>

							<ul>
								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Áo sơ mi nam
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Vest & Blazer
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Quần tây nam
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Áo polo
									</a>
								</li>

								<li class="bor18">
									<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
										Phụ kiện nam
									</a>
								</li>
							</ul>
						</div>

						<div class="p-t-65">
							<h4 class="mtext-112 cl2 p-b-33">
								Sản phẩm nổi bật
							</h4>

							<ul>
								<li class="flex-w flex-t p-b-30">
									<a href="#" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
										<img src="images/product-min-01.jpg" alt="SẢN PHẨM">
									</a>

									<div class="size-215 flex-col-t p-t-8">
										<a href="#" class="stext-116 cl8 hov-cl1 trans-04">
											Áo sơ mi trắng có chi tiết xếp ly sau lưng
										</a>

										<span class="stext-116 cl6 p-t-20">
											440.000₫
										</span>
									</div>
								</li>

								<li class="flex-w flex-t p-b-30">
									<a href="#" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
										<img src="images/product-min-02.jpg" alt="SẢN PHẨM">
									</a>

									<div class="size-215 flex-col-t p-t-8">
										<a href="#" class="stext-116 cl8 hov-cl1 trans-04">
											Giày Converse All Star Hi Canvas đen
										</a>

										<span class="stext-116 cl6 p-t-20">
											900.000₫
										</span>
									</div>
								</li>

								<li class="flex-w flex-t p-b-30">
									<a href="#" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
										<img src="images/product-min-03.jpg" alt="SẢN PHẨM">
									</a>

									<div class="size-215 flex-col-t p-t-8">
										<a href="#" class="stext-116 cl8 hov-cl1 trans-04">
											Đồng hồ da Porter Nixon màu nâu
										</a>

										<span class="stext-116 cl6 p-t-20">
											390.000₫
										</span>
									</div>
								</li>
							</ul>
						</div>

						<div class="p-t-55">
							<h4 class="mtext-112 cl2 p-b-20">
								Archive
							</h4>

							<ul>
								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											July 2018
										</span>

										<span>
											(9)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											June 2018
										</span>

										<span>
											(39)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											May 2018
										</span>

										<span>
											(29)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											April  2018
										</span>

										<span>
											(35)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											March 2018
										</span>

										<span>
											(22)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											February 2018
										</span>

										<span>
											(32)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											January 2018
										</span>

										<span>
											(21)
										</span>
									</a>
								</li>

								<li class="p-b-7">
									<a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
										<span>
											December 2017
										</span>

										<span>
											(26)
										</span>
									</a>
								</li>
							</ul>
						</div>

						<div class="p-t-50">
							<h4 class="mtext-112 cl2 p-b-27">
								Tags
							</h4>

							<div class="flex-w m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Fashion
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Lifestyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Denim
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Streetstyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Crafts
								</a>
							</div>
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
						LIÊN HỆ
					</h4>

					<p class="stext-107 cl7 size-201">
						Có câu hỏi? Hãy ghé thăm chúng tôi tại tầng 8, 379 Hudson St, New York, NY 10018 hoặc gọi cho chúng tôi theo số (+1) 96 716 6879
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
					Copyright &copy;<script>document.write(new Date().getFullYear());</script> 
					All rights reserved | This template is made with 
					<i class="fa fa-heart-o" aria-hidden="true"></i> 
					by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
	<script src="js/main.js"></script>
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
