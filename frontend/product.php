<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';


// Hàm lấy tên hiển thị từ session
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
	<title>Sản phẩm</title>
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

							<li class="active-menu">
								<a href="product.php">Sản phẩm</a>
							</li>

							<li class="label1" data-label1="hot">
								<a href="shoping-cart.php">Khuyến mãi</a>
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
					<?php
$cart = $_SESSION['cart'] ?? [];
$badge = 0;
foreach ($cart as $it) {
    $badge += (int)($it['qty'] ?? 1);
}

$wishlist  = $_SESSION['wishlist'] ?? [];
$wishCount = count($wishlist);
?>
<div class="wrap-icon-header flex-w flex-r-m">
    <!-- Search -->
    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
        <i class="zmdi zmdi-search"></i>
    </div>

    <!-- Cart -->
    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 
                icon-header-noti js-show-cart"
         data-notify="<?php echo $badge; ?>">
        <i class="zmdi zmdi-shopping-cart"></i>
    </div>

    <!-- Wishlist -->
    <a href="javascript:void(0);"
       class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 
              icon-header-noti js-show-wishlist"
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
							<div class="flex-c-m p-lr-10 trans-04">
								<span style="margin-right:6px;
											display:inline-flex;align-items:center;justify-content:center;
											width:26px;height:26px;border-radius:50%;
											background:#555;color:#fff;font-size:13px;font-weight:bold;">
									<?php echo htmlspecialchars($firstChar); ?>
								</span>
								<span><?php echo htmlspecialchars($displayName); ?></span>
							</div>

							<a href="logout.php" class="flex-c-m p-lr-10 trans-04">
								Đăng xuất
							</a>

						<?php else: ?>
							<a href="login.php" class="flex-c-m p-lr-10 trans-04">
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
					<ul class="sub-menu-m">
						<li><a href="index.php">Trang chủ 1</a></li>
						<li><a href="home-02.php">Trang chủ 2</a></li>
						<li><a href="home-03.php">Trang chủ 3</a></li>
					</ul>
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>

				<li>
					<a href="product.php">Sản phẩm</a>
				</li>

				<li>
					<a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Khuyến mãi</a>
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
	
	<!-- Product -->
	 <?php
// Lấy danh sách sản phẩm + hình
$sqlProducts = "
    SELECT sp.ma_sp, sp.ten_sp, sp.gia, sp.ma_loaisp,
           MIN(img.ten_anh) AS ten_anh
    FROM san_pham sp
    LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp
    WHERE sp.ton_tai = b'1'
    GROUP BY sp.ma_sp, sp.ten_sp, sp.gia, sp.ma_loaisp
    ORDER BY sp.ma_sp DESC
";
$products = mysqli_query($conn, $sqlProducts);

if (!$products) {
    die('Lỗi truy vấn sản phẩm: ' . mysqli_error($conn));
}
?>
<?php
// Map ma_loaisp trong DB -> class để Isotope lọc
$categoryClassMap = [
    1 => 'shirts',       // Áo sơ mi nam
    2 => 'suits',        // Vest & Blazer
    3 => 'pants',        // Quần tây nam
    4 => 'polo',         // Áo polo
    5 => 'accessories'   // Phụ kiện nam
];
?>
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
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
						Tìm kiếm
					</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input id="search-product" 
							class="mtext-107 cl2 size-114 plh2 p-r-15" 
							type="text" 
							name="search-product" 
							placeholder="Tìm kiếm sản phẩm...">
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
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Mặc định
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Phổ biến
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Đánh giá trung bình
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Mới nhất
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Giá: Thấp → Cao
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Giá: Cao → Thấp
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
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active js-filter-price" data-price="*">
										Tất cả
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 js-filter-price" data-price="0-1150000">
										0₫ - 1.150.000₫
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 js-filter-price" data-price="1150000-2300000">
										1.150.000₫ - 2.300.000₫
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 js-filter-price" data-price="2300000-3450000">
										2.300.000₫ - 3.450.000₫
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 js-filter-price" data-price="3450000-4600000">
										3.450.000₫ - 4.600.000₫
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 js-filter-price" data-price="4600000+">
										Trên 4.600.000₫
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
								Thẻ
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thời trang
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Phong cách sống
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Jeans
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Phong cách đường phố
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Thủ công
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

	  <div class="row isotope-grid">
   <?php
$wishlist = $_SESSION['wishlist'] ?? [];   // lấy 1 lần bên ngoài
?>

<?php if (mysqli_num_rows($products) == 0): ?>
    <p class="p-l-15">Chưa có sản phẩm nào trong hệ thống.</p>
<?php else: ?>
    <?php while ($row = mysqli_fetch_assoc($products)):
        // kiểm tra sp này có trong wishlist không
        $isLiked = isset($wishlist[$row['ma_sp']]);
    ?>

            <?php 
			 $catClass = isset($categoryClassMap[$row['ma_loaisp']])
                ? $categoryClassMap[$row['ma_loaisp']]
                : 'men';  // nếu không map được thì cho vào nhóm chung 'men'
                // Nếu chưa có ảnh thì dùng ảnh mặc định
                $imgFile = !empty($row['ten_anh']) ? $row['ten_anh'] : 'product-01.jpg';
            ?>
          <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo $catClass; ?>"
     data-price="<?php echo (int)$row['gia']; ?>">
                <div class="block2">
                    <div class="block2-pic hov-img0">
                        <img src="images/<?php echo htmlspecialchars($imgFile); ?>" alt="IMG-PRODUCT">

                        <a href="product-detail.php?id=<?php echo $row['ma_sp']; ?>"
                           class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                            Xem chi tiết
                        </a>
                    </div>

                    <div class="block2-txt flex-w flex-t p-t-14">
                        <div class="block2-txt-child1 flex-col-l ">
                            <a href="product-detail.php?id=<?php echo $row['ma_sp']; ?>"
                               class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                <?php echo htmlspecialchars($row['ten_sp']); ?>
                            </a>

                            <span class="stext-105 cl3">
                                <?php
                                if ($row['gia'] !== null) {
                                    echo number_format($row['gia']) . '₫';
                                } else {
                                    echo 'Liên hệ';
                                }
                                ?>
                            </span>
                        </div>

                     <div class="block2-txt-child2 flex-r p-t-3">
    <a href="wishlist_action.php?id=<?php echo (int)$row['ma_sp']; ?>"
       class="btn-addwish-b2 dis-block pos-relative
    <?php echo $isLiked ? 'js-addedwish-b2' : ''; ?>">
            <!-- ĐÃ YÊU THÍCH: hiện tim xanh -->
           <img class="icon-heart1 dis-block trans-04"
             src="images/icons/icon-heart-01.png" alt="Yêu thích">

        <!-- Tim xanh (khi đã yêu thích) -->
        <img class="icon-heart2 dis-block trans-04 ab-t-l"
             src="images/icons/icon-heart-02.png" alt="Đã yêu thích">
    </a>
</div>


                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>



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
						Có câu hỏi? Hãy đến cửa hàng của chúng tôi tại Số 1 Võ Văn Ngân, P. Linh Chiểu, TP. Thủ Đức, TP.HCM hoặc gọi cho chúng tôi theo số (+84) 123 456 789
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
						Bản tin
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

	<!-- Modal1 -->
	<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
		<div class="overlay-modal1 js-hide-modal1"></div>

		<div class="container">
			<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
				<button class="how-pos3 hov3 trans-04 js-hide-modal1">
					<img src="images/icons/icon-close.png" alt="CLOSE">
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
											<img src="images/product-detail-01.jpg" alt="IMG-PRODUCT">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-01.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-02.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-02.jpg" alt="IMG-PRODUCT">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/product-detail-02.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-03.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-03.jpg" alt="IMG-PRODUCT">

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
								1.352.000₫
							</span>

							<p class="stext-102 cl3 p-t-23">
								Mô tả ngắn: Áo khoác nhẹ, thoáng mát và phù hợp cho nhiều dịp. Liên hệ để biết thêm chi tiết.
							</p>
							
							<!--  -->
							<div class="p-t-33">
								<div class="flex-w flex-r-m p-b-10">
									<div class="size-203 flex-c-m respon6">
										Size
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

										<a href="cart_action.php?id=<?php echo $product['ma_sp']; ?>"
   class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
    Thêm vào giỏ
</a>

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
	//	$('.js-addwish-b2, .js-addwish-detail').on('click', function(e){
		//	e.preventDefault();
		//});

		$('.js-addwish-b2').each(function(){
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function(){
				swal(nameProduct, "đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function(){
				swal(nameProduct, "đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

		$('.js-addcart-detail').each(function(){
			var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
			$(this).on('click', function(){
				swal(nameProduct, "đã được thêm vào giỏ hàng!", "success");
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
<script>
$(function () {
    // Khởi tạo isotope (nếu main.js đã khởi tạo rồi thì gọi lại cũng không sao)
    var $grid = $('.isotope-grid').isotope({
        itemSelector: '.isotope-item',
        layoutMode: 'fitRows'
    });

    var filterCategory = '*';  // danh mục (áo sơ mi, vest,...)
    var filterPrice    = '*';  // giá
    var searchText     = '';   // từ khóa

    // Hàm apply filter chung
    function applyFilter() {
        $grid.isotope({
            filter: function () {
                var $item = $(this);

                // 1. Lọc theo danh mục (class .shirts, .suits,...)
                var okCat = true;
                if (filterCategory !== '*') {
                    okCat = $item.is(filterCategory);
                }

                // 2. Lọc theo giá (đọc từ data-price)
                var price  = parseInt($item.data('price'), 10) || 0;
                var okPrice = true;

                if (filterPrice !== '*') {
                    if (filterPrice.indexOf('-') > -1) {
                        var parts = filterPrice.split('-');
                        var min = parseInt(parts[0], 10);
                        var max = parseInt(parts[1], 10);
                        okPrice = price >= min && price <= max;
                    } else if (filterPrice.indexOf('+') > -1) {
                        var minPlus = parseInt(filterPrice, 10);
                        okPrice = price >= minPlus;
                    }
                }

                // 3. Tìm kiếm theo tên sản phẩm
                var okSearch = true;
                if (searchText !== '') {
                    var name = $item.find('.js-name-b2').text().toLowerCase();
                    okSearch = name.indexOf(searchText) !== -1;
                }

                return okCat && okPrice && okSearch;
            }
        });
    }

    /* ====== Lọc theo danh mục (hàng button trên cùng) ====== */
    // Hủy click cũ trong main.js và gắn lại
    $('.filter-tope-group button').off('click').on('click', function () {
        $('.filter-tope-group button').removeClass('how-active1');
        $(this).addClass('how-active1');

        filterCategory = $(this).data('filter'); // *, .shirts, .suits,...
        applyFilter();
    });

    /* ====== Lọc theo giá ====== */
    $('.js-filter-price').on('click', function (e) {
        e.preventDefault();
        $('.js-filter-price').removeClass('filter-link-active');
        $(this).addClass('filter-link-active');

        filterPrice = $(this).data('price'); // *, 0-1150000, 4600000+ ...
        applyFilter();
    });

    /* ====== Tìm kiếm theo tên sản phẩm ====== */
    $('#search-product').on('keyup', function () {
        searchText = $(this).val().toLowerCase().trim();
        applyFilter();
    });
	  // === Panel YÊU THÍCH: mở / đóng ===
	$('.js-show-wishlist').on('click', function () {
        $('.js-panel-wishlist').addClass('show-header-cart'); // dùng cùng class mở với cart
    });

    $('.js-hide-wishlist').on('click', function () {
        $('.js-panel-wishlist').removeClass('show-header-cart');
    });
});
</script>
	

</body>
</html>
