<?php
session_start();
include 'db.php';

// Hàm lấy tên hiển thị
function getDisplayName($sessionValue, $default = 'Tài khoản')
{
	if (is_array($sessionValue)) {
		if (!empty($sessionValue['username']))
			return $sessionValue['username'];
		if (!empty($sessionValue['HoTen']))
			return $sessionValue['HoTen'];
	} elseif (!empty($sessionValue)) {
		return $sessionValue;
	}
	return $default;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
	<title>Chi tiết sản phẩm</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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

<?php
// Lấy id từ URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
	echo "<h2 style='text-align:center;color:red;margin-top:40px'>Sản phẩm không tồn tại!</h2>";
	exit;
}

// Lấy sản phẩm + 1 hình ảnh
$sql = "
    SELECT 
        sp.ma_sp, 
        sp.ten_sp, 
        sp.gia, 
        sp.mota,
        sp.ma_loaisp,          -- khóa ngoại tới bảng loai_sp
        l.ten_loai,            -- tên loại
        MIN(img.ten_anh) AS ten_anh
    FROM san_pham sp
    LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp
    LEFT JOIN loai_sp l      ON sp.ma_loaisp = l.ma_loaisp
    WHERE sp.ma_sp = $id
    GROUP BY 
        sp.ma_sp, sp.ten_sp, sp.gia, sp.mota,
        sp.ma_loaisp, l.ten_loai
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
	echo "<h2 style='text-align:center;color:red;margin-top:40px'>Không tìm thấy thông tin sản phẩm!</h2>";
	exit;
}


$product = mysqli_fetch_assoc($result);

// Lấy kích cỡ từ DB
$sizes = [];
$sql_size = "
    SELECT kc.ten_kichco
    FROM kich_co kc
    JOIN kichco_sp ks ON kc.ma_kichco = ks.ma_kichco
    WHERE ks.ma_sp = $id
";
$rs_size = mysqli_query($conn, $sql_size);
if ($rs_size) {
	while ($row = mysqli_fetch_assoc($rs_size)) {
		$sizes[] = $row['ten_kichco'];
	}
}

// Lấy màu sắc từ DB
$colors = [];
$sql_color = "
    SELECT ms.ten_mau
    FROM mau_sac ms
    JOIN mau_sp msp ON ms.ma_mau = msp.ma_mau
    WHERE msp.ma_sp = $id
";
$rs_color = mysqli_query($conn, $sql_color);
if ($rs_color) {
	while ($row = mysqli_fetch_assoc($rs_color)) {
		$colors[] = $row['ten_mau'];
	}
}

// Lấy tất cả ảnh của sản phẩm
$images = [];
$sqlImg = "SELECT ten_anh FROM hinhanh_sp WHERE ma_sp = $id";
$rsImg = mysqli_query($conn, $sqlImg);

while ($rowImg = mysqli_fetch_assoc($rsImg)) {
	$images[] = $rowImg['ten_anh'];
}

// Nếu không có ảnh thì dùng 1 ảnh mặc định
if (empty($images)) {
	$images[] = 'product-01.jpg'; // bạn có thể copy file này vào thư mục images/products
}
?>

<body class="animsition">

	<!-- Header -->
	<header class="header-v4">
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
							Trợ giúp & FAQs
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
								<ul class="sub-menu">
									<li><a href="index.php">Trang chủ 1</a></li>
								</ul>
							</li>

							<li class="label1" data-label1="hot">
								<a href="product.php">Sản phẩm</a>
							</li>

							<li>
								<a href="shoping-cart.php">Giỏ hàng</a>
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
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
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
			<div class="wrap-icon-header flex-w flex-r-m">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<?php
				// Tính tổng số lượng trong giỏ để hiển thị badge
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
						Miễn phí vận chuyển cho đơn hàng từ 2.000.000₫
					</div>
				</li>

				<li>
					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							Trợ giúp & FAQs
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
					</ul>
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>

				<li class="label1 rs1" data-label1="hot">
					<a href="product.php">Sản phẩm</a>
				</li>

				<li>
					<a href="shoping-cart.php">Giỏ hàng</a>
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
	<?php include 'search_modal.php'; ?>



	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Trang chủ
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="product.php?category=<?php echo (int) $product['ma_loaisp']; ?>"
				class="stext-109 cl8 hov-cl1 trans-04">
				<?php echo htmlspecialchars($product['ten_loai'] ?? 'Danh mục'); ?>
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>


			<span class="stext-109 cl4">
				<?php echo htmlspecialchars($product['ten_sp']); ?>
			</span>

		</div>
	</div>


	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
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
			</div>


			<div class="col-md-6 col-lg-5 p-b-30">
				<div class="p-r-50 p-t-5 p-lr-0-lg">
					<h4 class="mtext-105 cl2 js-name-detail p-b-14">
						<?php echo htmlspecialchars($product['ten_sp']); ?>
					</h4>

					<span class="mtext-106 cl2">
						<?php
						if ($product['gia'] !== null) {
							echo number_format($product['gia']) . "₫";
						} else {
							echo "Liên hệ";
						}
						?>
					</span>

					<p class="stext-102 cl3 p-t-23">
						<?php
						echo !empty($product['mota'])
							? nl2br(htmlspecialchars($product['mota']))
							: "Sản phẩm chưa có mô tả.";
						?>
					</p>


					<!--  -->
					<div class="p-t-33">
						<!-- Kích cỡ -->
						<div class="flex-w flex-r-m p-b-10">
							<div class="size-203 flex-c-m respon6">
								Kích cỡ
							</div>

							<div class="size-204 respon6-next">
								<div class="rs1-select2 bor8 bg0">
									<select class="js-select2" name="size">
										<option value="">Chọn kích cỡ</option>
										<?php if (!empty($sizes)): ?>
											<?php foreach ($sizes as $kc): ?>
												<option><?php echo htmlspecialchars($kc); ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<option disabled>Đang cập nhật</option>
										<?php endif; ?>
									</select>
									<div class="dropDownSelect2"></div>
								</div>
							</div>
						</div>

						<!-- Màu sắc -->
						<div class="flex-w flex-r-m p-b-10">
							<div class="size-203 flex-c-m respon6">
								Màu sắc
							</div>

							<div class="size-204 respon6-next">
								<div class="rs1-select2 bor8 bg0">
									<select class="js-select2" name="color">
										<option value="">Chọn màu sắc</option>
										<?php if (!empty($colors)): ?>
											<?php foreach ($colors as $mau): ?>
												<option><?php echo htmlspecialchars($mau); ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<option disabled>Đang cập nhật</option>
										<?php endif; ?>
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

									<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product"
										value="1">

									<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
										<i class="fs-16 zmdi zmdi-plus"></i>
									</div>
								</div>

								<button
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-add-to-cart"
									data-id="<?php echo $product['ma_sp']; ?>">
									Thêm vào giỏ
								</button>
							</div>
						</div>
					</div>

					<?php
					if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {

						$rating = (int) $_POST['rating'];
						$name = mysqli_real_escape_string($conn, $_POST['name']);
						$email = mysqli_real_escape_string($conn, $_POST['email']);
						$review = mysqli_real_escape_string($conn, $_POST['review']);
						$date = date('Y-m-d');

						$sqlInsert = "INSERT INTO danh_gia (noidung_dg, diem_dg, ngay_danhgia) 
													VALUES ('$review', $rating, '$date')";

						if (mysqli_query($conn, $sqlInsert)) {
							echo "<script>alert('Cảm ơn bạn đã gửi đánh giá!'); window.location.reload();</script>";
							exit;
						} else {
							echo "<script>alert('Lỗi gửi đánh giá!');</script>";
						}
					}
					?>

					<form class="w-full" method="POST">
						<h5 class="mtext-108 cl2 p-b-7">Thêm đánh giá</h5>

						<div class="flex-w flex-m p-t-20 p-b-23">
							<span class="stext-102 cl3 m-r-16">Đánh giá của bạn *</span>
							<span class="wrap-rating fs-18 cl11 pointer">
								<i class="item-rating pointer zmdi zmdi-star-outline" data-value="1"></i>
								<i class="item-rating pointer zmdi zmdi-star-outline" data-value="2"></i>
								<i class="item-rating pointer zmdi zmdi-star-outline" data-value="3"></i>
								<i class="item-rating pointer zmdi zmdi-star-outline" data-value="4"></i>
								<i class="item-rating pointer zmdi zmdi-star-outline" data-value="5"></i>

								<input class="dis-none" type="number" name="rating" id="rating_input" required>
							</span>
						</div>

						<div class="row p-b-25">
							<div class="col-12 p-b-5">
								<label class="stext-102 cl3" for="review">Nhận xét của bạn *</label>
								<textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"
									required></textarea>
							</div>

							<div class="col-sm-6 p-b-5">
								<label class="stext-102 cl3" for="name">Họ tên *</label>
								<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name" type="text" name="name"
									required>
							</div>

							<div class="col-sm-6 p-b-5">
								<label class="stext-102 cl3" for="email">Email *</label>
								<input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email" type="email" name="email"
									required>
							</div>
						</div>

						<button name="submit_review"
							class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
							Gửi đánh giá
						</button>
					</form>

				</div>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<!--  -->
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
					<script>document.write(new Date().getFullYear());</script> Mọi quyền được bảo lưu | Giao diện được
					thiết kế bằng <i class="fa fa-heart-o" aria-hidden="true"></i> bởi <a href="https://colorlib.com"
						target="_blank">Colorlib</a>
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
			</div>
			</div>
		</footer>

		<!--===============================================================================================-->
		<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
		<script>
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

						// xoá class tim xanh trong danh sách sản phẩm
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
			$('.js-addwish-b2, .js-addwish-detail').on('click', function (e) {
				e.preventDefault();
			});

			$('.js-addwish-b2').each(function () {
				var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
				$(this).on('click', function () {
					swal(nameProduct, "đã được thêm vào danh sách yêu thích !", "success");

					$(this).addClass('js-addedwish-b2');
					$(this).off('click');
				});
			});

			$('.js-addwish-detail').each(function () {
				var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

				$(this).on('click', function () {
					swal(nameProduct, "đã được thêm vào danh sách yêu thích !", "success");

					$(this).addClass('js-addedwish-detail');
					$(this).off('click');
				});
			});

			/*---------------------------------------------*/

			$('.js-addcart-detail').each(function () {
				var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
				$(this).on('click', function () {
					swal(nameProduct, "đã được thêm vào giỏ hàng !", "success");
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
		<!--===============================================================================================-->
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