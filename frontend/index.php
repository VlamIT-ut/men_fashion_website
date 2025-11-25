
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "<!-- DEBUG: PHP ĐANG CHẠY ĐƯỢC Ở ĐẦU FILE -->";

session_start();
include 'db.php';

// Kết nối DB an toàn
if (!isset($conn) || !$conn) {
    die("Không kết nối được CSDL");
}

$sql = "SELECT * FROM san_pham WHERE ton_tai = 1 ORDER BY ma_sp DESC";
$result_products = mysqli_query($conn, $sql);

if (!$result_products) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

// Hàm lấy tên hiển thị từ session (admin / user)
function getDisplayName($sessionValue, $default = 'Tài khoản') {
    if (is_array($sessionValue)) {
        // Nếu login.php lưu cả dòng user: $_SESSION['admin'] = $row;
        if (!empty($sessionValue['username'])) return $sessionValue['username'];
        if (!empty($sessionValue['HoTen']))    return $sessionValue['HoTen'];
    } else {
        // Nếu chỉ lưu chuỗi username: $_SESSION['admin'] = 'admin1';
        return $sessionValue;
    }
    return $default;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<title>Cửa hàng Nam</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Mua sắm quần áo và phụ kiện nam mới nhất — áo khoác, áo sơ mi, giày và túi. Giao hàng nhanh và đổi trả dễ dàng.">
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
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>


<body class="animsition">
	
	<!-- Header -->
	<header>
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<!-- Topbar -->
			<div class="top-bar">
				<div class="content-topbar flex-sb-m h-full container">
					<div class="left-top-bar">
						Miễn phí vận chuyển cho đơn hàng từ 2.000.000₫
					</div>

					<div class="right-top-bar flex-w h-full">
    <a href="#" class="flex-c-m trans-04 p-lr-25">
        Trợ giúp & Câu hỏi
    </a>

<?php if (isset($_SESSION['user'])): 
   $displayName = getDisplayName($_SESSION['user'], 'Thành viên');
   $firstChar   = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
    ?>
    <!-- Chỉ hiện thông tin khách hàng -->
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
        VN
    </a>

    <a href="#" class="flex-c-m trans-04 p-lr-25">
        VND
    </a>
                     </div>

				</div>
			</div>

			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">
					
					<!-- Logo desktop -->		
							<a href="#" class="logo">
						<img src="images/icons/logo-01.png" alt="Logo cửa hàng">
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li class="active-menu">
									<a href="index.php">Trang chủ</a>
							</li>

							<li>
									<a href="product.php">Cửa hàng</a>
							</li>

							<li class="label1" data-label1="hot">
									<a href="shoping-cart.php">Tính năng</a>
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

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"<?php
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
				<a href="index.php"><img src="images/icons/logo-01.png" alt="Logo cửa hàng"></a>
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
						Miễn phí vận chuyển cho đơn hàng từ 100$
					</div>
				</li>

				<li>
					<div class="right-top-bar flex-w h-full">
    <a href="#" class="flex-c-m trans-04 p-lr-25">
        Trợ giúp & Câu hỏi
    </a>

    <?php if (isset($_SESSION['user'])): 
     $displayName = getDisplayName($_SESSION['user'], 'Thành viên');
        // Lấy chữ cái đầu để làm avatar (dùng mb_substr cho tiếng Việt)
        $firstChar   = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
    ?>
    <!-- Chỉ hiện thông tin khách hàng -->
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
        VN
    </a>

    <a href="#" class="flex-c-m trans-04 p-lr-25">
        VND
    </a>
                     </div>

				</li>
			</ul>

			<ul class="main-menu-m">
				<li>
						<a href="index.php">Trang chủ</a>
					
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>

				<li>
					<a href="product.php">Cửa hàng</a>
				</li>

				<li>
					<a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Tính năng</a>
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
					<img src="images/icons/icon-close2.png" alt="Đóng">
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

		

	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				<div class="item-slick1" style="background-image: url(images/slide-01.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
										<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
											<span class="ltext-101 cl2 respon2">
												Thời Trang Nam Cao Cấp
											</span>
										</div>
					
											<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
												<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
													BST Thu Đông 2025
												</h2>
											</div>
					
										<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
											<a href="product.php" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
												Khám phá ngay
											</a>
										</div>
						</div>
					</div>
				</div>

				<div class="item-slick1" style="background-image: url(images/slide-02.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="rollIn" data-delay="0">
									<span class="ltext-101 cl2 respon2">
										Phong Cách Công Sở
									</span>
								</div>
						
									<div class="layer-slick1 animated visible-false" data-appear="lightSpeedIn" data-delay="800">
										<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
											Áo Vest & Quần Tây
										</h2>
										</div>
						
									<div class="layer-slick1 animated visible-false" data-appear="slideInUp" data-delay="1600">
										<a href="product.php" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
											Xem bộ sưu tập
										</a>
										</div>
						</div>
					</div>
				</div>

				<div class="item-slick1" style="background-image: url(images/slide-03.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft" data-delay="0">
									<span class="ltext-101 cl2 respon2">
										Phong Cách Trẻ
									</span>
								</div>
						
								<div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
										Thời Trang Đường Phố
									</h2>
									</div>
						
								<div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
									<a href="product.php" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
										Khám phá ngay
									</a>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- Banner -->
	<div class="sec-banner bg0 p-t-80 p-b-50">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/banner-01.jpg" alt="Banner Áo">

						<a href="product.php" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Áo Nam
								</span>

								<span class="block1-info stext-102 trans-04">
									Sơ mi & Polo
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Mua ngay
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/banner-02.jpg" alt="Banner Vest">

						<a href="product.php" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Vest & Blazer
								</span>

								<span class="block1-info stext-102 trans-04">
									Phong cách lịch lãm
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Mua ngay
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/banner-03.jpg" alt="Banner Phụ kiện">

						<a href="product.php" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Phụ kiện Nam
								</span>

								<span class="block1-info stext-102 trans-04">
									Thắt lưng & Ví da
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Mua ngay
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5">
					Sản phẩm nổi bật
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Tất cả sản phẩm
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".shirts">
						Áo sơ mi nam
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".suits">
						Vest & Blazer
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".pants">
						Quần tây nam
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".polo">
						Áo polo
					</button>

					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".accessories">
						Phụ kiện nam
					</button>
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
							 Bộ lọc
						</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Tìm
						</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Tìm sản phẩm...">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Sắp xếp theo
							</div>

							<ul>
								<ul class="main-menu">
									<li class="active-menu">
										<a href="index.php">Trang chủ</a>
									</li>

									<li>
										<a href="product.php">Cửa hàng</a>
									</li>

									<li class="label1" data-label1="hot">
										<a href="shoping-cart.php">Tính năng</a>
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
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Giá: Cao đến Thấp
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Giá
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Tất cả
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$0.00 - $50.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$50.00 - $100.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$100.00 - $150.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$150.00 - $200.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$200.00+
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Màu sắc
							</div>

							<ul>
								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #222;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
											Đen
										</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #4272d7;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Xanh
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #b3b3b3;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Xám
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #00ad5f;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Xanh lá
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #fa4251;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Đỏ
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #aaa;">
										<i class="zmdi zmdi-circle-o"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Trắng
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col4 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Tags
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thời trang
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Lối sống
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Jean
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thời trang đường phố
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thủ công
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
            <!-- ====== LIST SẢN PHẨM NỔI BẬT ====== -->
            <div class="row isotope-grid">
                <?php
                // Nếu cần chắc chắn con trỏ ở đầu result
                if ($result_products && mysqli_num_rows($result_products) > 0) {
                    mysqli_data_seek($result_products, 0);
                    while ($p = mysqli_fetch_assoc($result_products)) {

                        // Lấy 1 ảnh cho sản phẩm
                        $img = 'product-01.jpg'; // ảnh mặc định
                        $sqlImg = "SELECT ten_anh FROM hinhanh_sp 
                                   WHERE ma_sp = " . (int)$p['ma_sp'] . " 
                                   LIMIT 1";
                        $rsImg = mysqli_query($conn, $sqlImg);
                        if ($rsImg && mysqli_num_rows($rsImg) > 0) {
                            $rowImg = mysqli_fetch_assoc($rsImg);
                            if (!empty($rowImg['ten_anh'])) {
                                $img = $rowImg['ten_anh'];
                            }
                        }

                        // Nếu có cột loại sản phẩm thì map ra class cho isotope
                        // Ở đây mình để đơn giản: tất cả đều là .shirts
                        $itemClass = 'shirts';
                ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo $itemClass; ?>">
                    <div class="block2">
                        <div class="block2-pic hov-img0">
                            <img src="images/products/<?php echo htmlspecialchars($img); ?>" alt="IMG-PRODUCT">

                            <a href="product-detail.php?id=<?php echo (int)$p['ma_sp']; ?>"
                               class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                Xem chi tiết
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="product-detail.php?id=<?php echo (int)$p['ma_sp']; ?>"
                                   class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    <?php echo htmlspecialchars($p['ten_sp']); ?>
                                </a>

                                <span class="stext-105 cl3">
                                    <?php
                                    if ($p['gia'] !== null) {
                                        echo number_format($p['gia']) . '₫';
                                    } else {
                                        echo 'Liên hệ';
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                    <img class="icon-heart1 dis-block trans-04"
                                         src="images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                         src="images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } // end while
                } else {
                    echo '<div class="col-12 p-t-40 p-b-40 text-center">
                            Chưa có sản phẩm nào. <a href="product.php" class="cl1">Xem tất cả sản phẩm</a>
                          </div>';
                }
                ?>
            </div>
            <!-- ====== /LIST SẢN PHẨM NỔI BẬT ====== -->


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
								Hoàn trả
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Vận chuyển
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Câu hỏi thường gặp
							</a>
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						LIÊN HỆ
					</h4>

					<p class="stext-107 cl7 size-201">
						Có câu hỏi? Hãy liên hệ cửa hàng tại tầng 8, 379 Hudson St, New York, NY 10018 hoặc gọi số (+1) 96 716 6879
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
						Nhận bản tin
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
						<img src="images/icons/icon-pay-01.png" alt="Biểu tượng thanh toán">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-02.png" alt="Biểu tượng thanh toán">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-03.png" alt="Biểu tượng thanh toán">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-04.png" alt="Biểu tượng thanh toán">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-05.png" alt="Biểu tượng thanh toán">
					</a>
				</div>

				<p class="stext-107 cl6 txt-center">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Bản quyền &copy;<script>document.write(new Date().getFullYear());</script> Mọi quyền được bảo lưu | Giao diện được thiết kế bằng <i class="fa fa-heart-o" aria-hidden="true"></i> bởi <a href="https://colorlib.com" target="_blank">Colorlib</a>
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

	<!-- Modal1 -->
	<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
		<div class="overlay-modal1 js-hide-modal1"></div>

		<div class="container">
			<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
				<button class="how-pos3 hov3 trans-04 js-hide-modal1">
					<img src="images/icons/icon-close.png" alt="Đóng">
				</button>

				<div class="row">
					<div class="col-md-6 col-lg-7 p-b-30">
						<div class="p-l-25 p-r-30 p-lr-0-lg">
							<div class="wrap-slick3 flex-sb flex-w">
								<div class="wrap-slick3-dots"></div>
								<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

								<div class="slick3 gallery-lb">
									<div class="item-slick3" data-thumb="images/product-detail-01.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-01.jpg" alt="Hình sản phẩm">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-01.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-02.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-02.jpg" alt="Hình sản phẩm">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-02.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-03.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-03.jpg" alt="Hình sản phẩm">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-03.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6 col-lg-5 p-b-30">
						<div class="p-r-50 p-t-5 p-lr-0-lg">
							<h4 class="mtext-105 cl2 js-name-detail p-b-14">
								Áo khoác nhẹ
							</h4>

							<span class="mtext-106 cl2">
								$58.79
							</span>

							<p class="stext-102 cl3 p-t-23">
								Mô tả sản phẩm: Chất liệu cao cấp, thiết kế hiện đại, thoải mái khi mặc và dễ phối đồ cho nhiều dịp.
							</p>
							
							<!--  -->
							<div class="p-t-33">
								<div class="flex-w flex-r-m p-b-10">
									<div class="size-203 flex-c-m respon6">
										Kích cỡ
									</div>

									<div class="size-204 respon6-next">
										<div class="rs1-select2 bor8 bg0">
											<select class="js-select2" name="time">
												<option>Chọn</option>
												<option>Size S</option>
												<option>Size M</option>
												<option>Size L</option>
												<option>Size XL</option>
											</select>
											<div class="dropDownSelect2"></div>
										</div>
									</div>
								</div>

								<div class="flex-w flex-r-m p-b-10">
									<div class="size-203 flex-c-m respon6">
										Màu sắc
									</div>

									<div class="size-204 respon6-next">
										<div class="rs1-select2 bor8 bg0">
											<select class="js-select2" name="time">
												<option>Chọn</option>
												<option>Đỏ</option>
												<option>Xanh</option>
												<option>Trắng</option>
												<option>Xám</option>
											</select>
											<div class="dropDownSelect2"></div>
										</div>
									</div>
								</div>

								<div class="flex-w flex-r-m p-b-10">
									<div class="size-204 flex-w flex-m respon6-next">
										<div class="wrap-num-product flex-w m-r-20 m-tb-10">
											<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-minus"></i>
											</div>

											<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">

											<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-plus"></i>
											</div>
										</div>

										<button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
											Thêm vào giỏ
										</button>
									</div>
								</div>	
							</div>

							<!--  -->
							<div class="flex-w flex-m p-l-100 p-t-40 respon7">
								<div class="flex-m bor9 p-r-10 m-r-11">
									<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Thêm vào yêu thích">
										<i class="zmdi zmdi-favorite"></i>
									</a>
								</div>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
									<i class="fa fa-facebook"></i>
								</a>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
									<i class="fa fa-twitter"></i>
								</a>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
									<i class="fa fa-google-plus"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/slick/slick.min.js"></script>
	<script src="js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script src="vendor/parallax100/parallax100.js"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/sweetalert/sweetalert.min.js"></script>
	<script>
		$('.js-addwish-b2').on('click', function(e){
			e.preventDefault();
		});

			$('.js-addwish-b2').each(function(){
				var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
				$(this).on('click', function(){
					swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

					$(this).addClass('js-addedwish-b2');
					$(this).off('click');
				});
			});

			$('.js-addwish-detail').each(function(){
				var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

				$(this).on('click', function(){
					swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

					$(this).addClass('js-addedwish-detail');
					$(this).off('click');
				});
			});

			/*---------------------------------------------*/

			$('.js-addcart-detail').each(function(){
				var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
				$(this).on('click', function(){
					swal(nameProduct, " đã được thêm vào giỏ hàng!", "success");
				});
			});
	
	</script>
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

	<?php if (!empty($_SESSION['show_cart'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btnCart = document.querySelector('.js-show-cart');
    if (btnCart) {
        btnCart.click();        // Gọi đúng hàm toggle cart của template
    }
});
</script>
<?php unset($_SESSION['show_cart']); endif; ?>
</body>
</html>