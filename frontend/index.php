<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Kết nối DB an toàn
if (!isset($conn) || !$conn) {
	die("Không kết nối được CSDL");
}

// Hàm lấy tên hiển thị từ session (admin / user)
function getDisplayName($sessionValue, $default = 'Tài khoản')
{
	if (is_array($sessionValue)) {
		// Nếu login.php lưu cả dòng user: $_SESSION['admin'] = $row;
		if (!empty($sessionValue['username']))
			return $sessionValue['username'];
		if (!empty($sessionValue['HoTen']))
			return $sessionValue['HoTen'];
	} else {
		// Nếu chỉ lưu chuỗi username: $_SESSION['admin'] = 'admin1';
		return $sessionValue;
	}
	return $default;
}

// === PHẦN XỬ LÝ AJAX QUICK VIEW (NẰM NGAY ĐẦU) ===
if (isset($_POST['quick_view_ajax']) && isset($_POST['ma_sp'])) {

	// Kết nối lại nếu cần (đề phòng include bên trên chưa chạy tới)
	if (!isset($conn))
		include 'db.php';

	$ma_sp = (int) $_POST['ma_sp'];

	// 1. Query lấy thông tin
	$sql = "SELECT sp.*, MIN(img.ten_anh) as ten_anh 
            FROM san_pham sp 
            LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp 
            WHERE sp.ma_sp = $ma_sp 
            GROUP BY sp.ma_sp";
	$rs = mysqli_query($conn, $sql);

	if ($rs && mysqli_num_rows($rs) > 0) {
		$p = mysqli_fetch_assoc($rs);

		// Lấy danh sách ảnh
		$sqlImg = "SELECT ten_anh FROM hinhanh_sp WHERE ma_sp = $ma_sp";
		$rsImg = mysqli_query($conn, $sqlImg);
		$images = [];
		while ($row = mysqli_fetch_assoc($rsImg)) {
			$images[] = $row['ten_anh'];
		}
		if (empty($images))
			$images[] = $p['ten_anh'] ?? 'product-01.jpg';

		// Lấy size/màu (Query mẫu)
		$sizes = ['S', 'M', 'L', 'XL']; // Tạm thời hardcode để test hiển thị trước
		$colors = ['Đỏ', 'Xanh', 'Trắng'];

		// --- XUẤT HTML RA CHO JS ---
		?>
		<div class="row">
			<div class="col-md-6 col-lg-7 p-b-30">
				<div class="p-l-25 p-r-30 p-lr-0-lg">
					<div class="wrap-slick3 flex-sb flex-w">
						<div class="wrap-slick3-dots"></div>
						<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
						<div class="slick3 gallery-lb">
							<?php foreach ($images as $img): ?>
								<div class="item-slick3" data-thumb="images/<?php echo htmlspecialchars($img); ?>">
									<div class="wrap-pic-w pos-relative">
										<img src="images/<?php echo htmlspecialchars($img); ?>" alt="IMG-PRODUCT">
										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
											href="images/<?php echo htmlspecialchars($img); ?>">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-5 p-b-30">
				<div class="p-r-50 p-t-5 p-lr-0-lg">
					<h4 class="mtext-105 cl2 js-name-detail p-b-14">
						<?php echo htmlspecialchars($p['ten_sp']); ?>
					</h4>
					<span class="mtext-106 cl2">
						<?php echo number_format($p['gia'], 0, ',', '.'); ?>₫
					</span>
					<p class="stext-102 cl3 p-t-23">
						<?php echo nl2br(htmlspecialchars($p['mota'] ?? '')); ?>
					</p>

					<div class="p-t-33">
						<div class="flex-w flex-r-m p-b-10">
							<div class="size-204 flex-w flex-m respon6-next">
								<div class="wrap-num-product flex-w m-r-20 m-tb-10">
									<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"><i
											class="fs-16 zmdi zmdi-minus"></i></div>
									<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product"
										value="1">
									<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"><i
											class="fs-16 zmdi zmdi-plus"></i></div>
								</div>
								<button
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail"
									data-id="<?php echo $p['ma_sp']; ?>">
									Thêm vào giỏ
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	} else {
		echo "Không tìm thấy sản phẩm.";
	}

	// QUAN TRỌNG: Dừng code ngay lập tức để không in ra trang chủ bên dưới
	exit;
}
// === KẾT THÚC PHẦN AJAX ===
?>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>

<!DOCTYPE html>
<html lang="vi">

<head>
	<title>Cửa hàng Nam</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description"
		content="Mua sắm quần áo và phụ kiện nam mới nhất — áo khoác, áo sơ mi, giày và túi. Giao hàng nhanh và đổi trả dễ dàng.">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.png" />
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
							$firstChar = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
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

							<li class="label1" data-label1="hot">
								<a href="product.php">Sản phẩm</a>
							</li>

							<li>
								<a href="shoping-cart.php">Giỏ hàng</a>
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

						<?php
						$cart = $_SESSION['cart'] ?? [];
						$badge = 0;
						foreach ($cart as $it) {
							$badge += (int) ($it['qty'] ?? 1);
						}
						?>
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
							data-notify="<?php echo $badge; ?>">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>

					</div>

					<?php
					$wishlist = $_SESSION['wishlist'] ?? [];
					$wishCount = count($wishlist);
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
					$badge += (int) ($it['qty'] ?? 1);
				}
				?> <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
					data-notify="<?php echo $badge; ?>">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<?php
				$wishlist = $_SESSION['wishlist'] ?? [];
				$wishCount = count($wishlist);
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
							$firstChar = mb_strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'), 'UTF-8');
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

				<form class="wrap-search-header flex-w p-l-15" method="get" action="product.php">
					<button class="flex-c-m trans-04">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="q" placeholder="Tìm kiếm sản phẩm..."
						value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
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
	<?php include 'search_modal.php'; ?>



	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				<div class="item-slick1" style="background-image: url(images/slide-03.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Thời trang TRENDY
								</span>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
								<a href="product.php"
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Khám phá ngay
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="item-slick1" style="background-image: url(images/slide-02.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft"
								data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Phong cách MỚI
								</span>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
								<a href="product.php"
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Khám phá ngay
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ====== LIST SẢN PHẨM MỚI ====== -->
	<?php
	// Lấy danh mục đang chọn
	$cat_active = isset($_GET['category']) ? $_GET['category'] : 'all';

	// Lấy danh mục từ bảng loai_sp
	$sqlCat = "SELECT ma_loaisp, ten_loai FROM loai_sp ORDER BY ma_loaisp ASC";
	$rsCat = mysqli_query($conn, $sqlCat);
	$categories = [];
	if ($rsCat) {
		while ($row = mysqli_fetch_assoc($rsCat)) {
			$categories[] = $row;
		}
	}

	// Build query sản phẩm
	$fromClause = " FROM san_pham sp LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp";

	$whereParts = ["sp.ton_tai = b'1'"];
	if ($cat_active !== 'all') {
		$whereParts[] = "sp.ma_loaisp = " . (int) $cat_active;
	}
	$whereSQL = " WHERE " . implode(" AND ", $whereParts);

	// Pagination chỉ chạy khi all
	$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
	$perPage = 9;
	$offset = ($page - 1) * $perPage;
	$totalPages = 1;

	if ($cat_active === 'all') {
		$sqlCount = "SELECT COUNT(DISTINCT sp.ma_sp) AS total FROM san_pham sp WHERE sp.ton_tai = b'1'";
		$rsCount = mysqli_query($conn, $sqlCount);
		if ($rsCount) {
			$rowCount = mysqli_fetch_assoc($rsCount);
			$total = (int) $rowCount['total'];
			$totalPages = ceil($total / $perPage);
		}
	}

	$sqlProducts = "
    SELECT sp.ma_sp, sp.ten_sp, sp.gia,
           MIN(img.ten_anh) AS ten_anh
    $fromClause
    $whereSQL
    GROUP BY sp.ma_sp, sp.ten_sp, sp.gia
    ORDER BY sp.ma_sp DESC
    LIMIT $offset, $perPage
";
	$products = mysqli_query($conn, $sqlProducts);

	// Lấy wishlist để check icon tim
	$wishlist = $_SESSION['wishlist'] ?? [];
	?>
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">

			<div class="p-b-10">
				<h3 class="ltext-103 cl5">Sản phẩm mới</h3>
			</div>

			<!-- LỌC CATEGORY -->
			<div class="flex-w flex-l-m filter-tope-group m-tb-10">
				<a href="?category=all"
					class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?= ($cat_active == 'all' ? 'how-active1' : '') ?>">
					Tất cả sản phẩm
				</a>

				<?php foreach ($categories as $dm): ?>
					<a href="?category=<?= (int) $dm['ma_loaisp'] ?>"
						class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?= ($cat_active == $dm['ma_loaisp'] ? 'how-active1' : '') ?>">
						<?= htmlspecialchars($dm['ten_loai']) ?>
					</a>
				<?php endforeach; ?>
			</div>

			<!-- DANH SÁCH SẢN PHẨM -->
			<div class="row isotope-grid">
				<?php if (!$products || mysqli_num_rows($products) == 0): ?>
					<p class="p-l-15">Không tìm thấy sản phẩm nào.</p>
				<?php else: ?>
					<?php while ($row = mysqli_fetch_assoc($products)):
						$imgFile = !empty($row['ten_anh']) ? $row['ten_anh'] : 'product-01.jpg';
						$isLiked = isset($wishlist[$row['ma_sp']]);
						?>
						<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item">
							<div class="block2">
								<div class="block2-pic hov-img0">
									<img src="images/<?= htmlspecialchars($imgFile) ?>" alt="IMG-PRODUCT">
									<a href="javascript:void(0)"
										class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
										data-id="<?php echo (int) $row['ma_sp']; ?>">
										Xem nhanh
									</a>
								</div>

								<div class="block2-txt flex-w flex-t p-t-14">
									<div class="block2-txt-child1 flex-col-l">
										<a href="product-detail.php?id=<?= (int) $row['ma_sp'] ?>"
											class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
											<?= htmlspecialchars($row['ten_sp']) ?>
										</a>

										<span class="stext-105 cl3">
											<?= number_format($row['gia']) ?>₫
										</span>
									</div>

									<!-- ICON TIM (WISHLIST) -->
									<div class="block2-txt-child2 flex-r p-t-3">
										<a href="wishlist_action.php?id=<?= (int) $row['ma_sp'] ?>"
											class="btn-addwish-b2 dis-block pos-relative <?= ($isLiked ? 'js-addedwish-b2' : '') ?>">
											<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png"
												alt="ICON">
											<img class="icon-heart2 dis-block trans-04 ab-t-l"
												src="images/icons/icon-heart-02.png" alt="ICON">
										</a>
									</div>
								</div>

							</div>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>

			<!-- PHÂN TRANG CHỈ HIỆN KHI ALL -->
			<?php if ($cat_active === 'all' && $totalPages > 1): ?>
				<div class="flex-c-m flex-w w-full p-t-45">
					<?php for ($i = 1; $i <= $totalPages; $i++):
						$params = $_GET;
						$params['page'] = $i;
						$urlPage = '?' . http_build_query($params);
						$active = ($page == $i) ? 'text-white bg-dark' : '';
						?>
						<a href="<?= $urlPage ?>" class="flex-c-m how-pagination1 trans-04 m-all-7 <?= $active ?>">
							<?= $i ?>
						</a>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

		</div>
	</section>


	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
		<div class="container">

			<div class="col-sm-6 col-lg-3 p-b-50">
				<h4 class="stext-301 cl0 p-b-30">
					LIÊN HỆ
				</h4>

				<p class="stext-107 cl7 size-201">
					Địa chỉ: 70 Tô Ký, TPHCM
					<br>Điện thoại: +84 123 456 789
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
						<input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email"
							placeholder="email@example.com">
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
				Bản quyền &copy;
				<script>document.write(new Date().getFullYear());</script> Mọi quyền được bảo lưu | Giao diện được thiết
				kế bằng <i class="fa fa-heart-o" aria-hidden="true"></i> bởi <a href="https://colorlib.com"
					target="_blank">Colorlib</a>
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
					<img src="images/icons/icon-close.png" alt="CLOSE">
				</button>

				<div id="modal-content-loader">
					<!-- Nội dung sản phẩm sẽ được load bằng AJAX -->
				</div>
			</div>
		</div>
	</div>

	<script src="js/main.js"></script>

	<script>
		// QUICK VIEW AJAX
		$(document).on('click', '.js-show-modal1', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			$.post('product.php', {
				lay_thong_tin_sp_ajax: 1,
				ma_sp: id
			}, function (html) {
				$('#modal-content-loader').html(html);

				// Khởi tạo select2 trong modal
				$('.js-select2-modal').each(function () {
					$(this).select2({
						minimumResultsForSearch: 20,
						dropdownParent: $(this).next('.dropDownSelect2')
					});
				});

				$('.js-modal1').addClass('show-modal1');

				// Khởi tạo slick3 trong modal (cho gallery ảnh)
				$('.wrap-slick3').each(function () {
					$(this).find('.slick3').slick('unslick'); // nếu đã có, destroy
					$(this).find('.slick3').slick({
						slidesToShow: 1,
						slidesToScroll: 1,
						fade: false,
						dots: true,
						appendDots: $(this).find('.wrap-slick3-dots'),
						appendArrows: $(this).find('.wrap-slick3-arrows'),
						infinite: true,
						autoplay: false,
					});
				});
			});
		});

		// Sử dụng $(document).on để bắt sự kiện click cho cả phần tử tĩnh và động
		$(document).on('click', '.js-hide-modal1', function () {
			$('.js-modal1').removeClass('show-modal1');
		});
	</script>


	<script>
		// Thêm vào giỏ từ popup quick-view bằng AJAX
		$(document).on('click', '.js-addcart-detail', function (e) {
			e.preventDefault();

			var id = $(this).data('id');  // lấy data-id từ button
			var qty = $(this).closest('.flex-w').find('.num-product').val() || 1;
			qty = parseInt(qty, 10) || 1;

			$.post('cart_action.php', { id: id, qty: qty }, function (res) {
				if (res.status === 'success') {
					// Cập nhật mini-cart
					$('#mini-cart-container').html(res.mini_cart_html);

					// Cập nhật badge số lượng trên icon giỏ
					$('.js-show-cart').attr('data-notify', res.cart_count);

					// Mở panel mini-cart
					$('.js-panel-cart').addClass('show-header-cart');

					// Thông báo đẹp
					var nameProduct = $('.js-name-detail').first().text() || 'Sản phẩm';
					swal(nameProduct, "đã được thêm vào giỏ hàng!", "success");
				} else {
					alert(res.message || 'Lỗi thêm vào giỏ');
				}
			}, 'json').fail(function () {
				alert('Không gọi được cart_action.php');
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
	<!--===============================================================================================-->

	<script>
		// Bỏ thích ngay trong mini favourite (panel wishlist)
		// Bỏ thích ngay trong mini favourite (panel wishlist)
		$(document).on('click', '.js-remove-wish', function (e) {
			e.preventDefault();

			var id = $(this).data('id');

			$.post('wishlist_action.php', {
				id: id,
				action: 'remove',
				ajax: 1
			}, function (res) {
				if (res.status === 'success') {

					// Cập nhật lại HTML mini wishlist
					$('#mini-wishlist-container').html(res.mini_wishlist_html);

					// Cập nhật badge số tim trên icon header
					$('.js-show-wishlist').attr('data-notify', res.wish_count);

					// ✅ QUAN TRỌNG: xoá class tim xanh trong danh sách sản phẩm
					var heart = $('.btn-addwish-b2[href*="id=' + id + '"]');

					heart.removeClass('js-addedwish-b2');
				}
				else {
					alert(res.message || 'Có lỗi khi xoá khỏi yêu thích.');
				}
			}, 'json').fail(function () {
				alert('Không gọi được wishlist_action.php');
			});
		});

	</script>

	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function () {
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
		$('.gallery-lb').each(function () { // the containers for all your galleries
			$(this).magnificPopup({
				delegate: 'a', // the selector for gallery item
				type: 'image',
				gallery: {
					enabled: true
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
		$('.js-addwish-b2').on('click', function (e) {
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function () {
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function () {
				swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function () {
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function () {
				swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

		$('.js-addcart-detail').each(function () {
			var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
			$(this).on('click', function () {
				swal(nameProduct, " đã được thêm vào giỏ hàng!", "success");
			});
		});

	</script>
	<!--===============================================================================================-->
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function () {
			$(this).css('position', 'relative');
			$(this).css('overflow', 'hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function () {
				ps.update();
			})
		});

	</script>

	<script>
		// MỞ / ĐÓNG MINI WISHLIST (panel bên phải)
		$(document).on('click', '.js-show-wishlist', function (e) {
			e.preventDefault();
			$('.js-panel-wishlist').addClass('show-header-cart');
		});

		$(document).on('click', '.js-hide-wishlist', function (e) {
			e.preventDefault();
			$('.js-panel-wishlist').removeClass('show-header-cart');
		});

		// Nếu muốn hiệu ứng thả tim + swal giống product.php:
		$('.js-addwish-b2').on('click', function (e) {
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function () {
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function () {
				swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function () {
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function () {
				swal(nameProduct, " đã được thêm vào danh sách yêu thích!", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
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