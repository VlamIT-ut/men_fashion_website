<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Hàm lấy tên hiển thị từ session
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

// Hàm lấy mã màu CSS từ tên tiếng Việt
function getColorHex($colorName)
{
	$colorName = mb_strtolower(trim($colorName), 'UTF-8');
	switch ($colorName) {
		case 'đen': return '#1e293b';
		case 'trắng': return '#ffffff';
		case 'đỏ': return '#ef4444';
		case 'xanh': return '#2563eb';
		case 'xanh dương': return '#2563eb';
		case 'xanh lá': return '#22c55e';
		case 'xám': return '#64748b';
		case 'vàng': return '#eab308';
		case 'hồng': return '#ec4899';
		case 'cam': return '#ff7849';
		case 'nâu': return '#78350f';
		case 'tím': return '#a855f7';
		default: return '#cbd5e1';
	}
}

// AJAX Quick View đã được chuyển sang ajax_quickview.php để tối ưu tốc độ và UI
?>
				<!-- Modal1: Quick View -->
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



				<!DOCTYPE html>
				<html lang="vi">

				<head>
					<title>Sản phẩm</title>
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
									<?php
									$cart = $_SESSION['cart'] ?? [];
									$badge = 0;
									foreach ($cart as $it) {
										$badge += (int) ($it['qty'] ?? 1);
									}
									$wishlist = $_SESSION['wishlist'] ?? [];
									$wishCount = count($wishlist);
									?>
									<div class="wrap-icon-header flex-w flex-r-m">
										<div
											class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
											<i class="zmdi zmdi-search"></i>
										</div>

										<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 
									icon-header-noti js-show-cart" data-notify="<?php echo $badge; ?>">
											<i class="zmdi zmdi-shopping-cart"></i>
										</div>

										<a href="javascript:void(0);" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 
								  icon-header-noti js-show-wishlist" data-notify="<?php echo $wishCount; ?>">
											<i class="zmdi zmdi-favorite-outline"></i>
										</a>
									</div>

								</nav>
							</div>
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

					<?php if (!empty($_SESSION['show_wishlist'])): ?>
						<script>
							// tự mở panel wishlist sau khi thả tim xong
							$(function () {
								$('.js-panel-wishlist').addClass('show-header-cart');
							});
						</script>
						<?php unset($_SESSION['show_wishlist']); ?>
					<?php endif; ?>


					<?php
					/* ================== PHẦN FILTER & LẤY SẢN PHẨM ================== */

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

					// Các filter khác
					$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
					$price_from = isset($_GET['price_from']) ? (int) $_GET['price_from'] : null;
					$price_to = isset($_GET['price_to']) ? (int) $_GET['price_to'] : null;
					$color = isset($_GET['color']) ? trim($_GET['color']) : null;
					$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';


					// ORDER BY
					switch ($sort) {
						case 'newest':
							$orderBy = "ORDER BY sp.ma_sp DESC";
							break; // mới nhất = id lớn
						case 'price-asc':
							$orderBy = "ORDER BY sp.gia ASC";
							break;
						case 'price-desc':
							$orderBy = "ORDER BY sp.gia DESC";
							break;
						default:
							$orderBy = "";
					}

					// Build FROM + WHERE (có thể JOIN màu nếu filter màu)
					$whereParts = [];
					$whereParts[] = "sp.ton_tai = b'1'"; // chỉ lấy sản phẩm còn tồn tại
					$joinColor = false;

					if ($price_from !== null) {
						$whereParts[] = "sp.gia >= $price_from";
					}
					if ($price_to !== null) {
						$whereParts[] = "sp.gia <= $price_to";
					}
					if ($cat_active !== 'all') {
						$catInt = (int) $cat_active;
						$whereParts[] = "sp.ma_loaisp = $catInt";
					}
					if ($color) {
						$colorEsc = mysqli_real_escape_string($conn, $color);
						$joinColor = true;
						$whereParts[] = "ms.ten_mau = '$colorEsc'";
					}
					if ($keyword !== '') {
						$kwEsc = mysqli_real_escape_string($conn, $keyword);
						// tìm theo tên sản phẩm hoặc mô tả
						$whereParts[] = "(sp.ten_sp LIKE '%$kwEsc%' OR sp.mota LIKE '%$kwEsc%')";
					}


					$fromClause = " FROM san_pham sp";
					if ($joinColor) {
						$fromClause .= "
        JOIN mau_sp msp ON sp.ma_sp = msp.ma_sp
        JOIN mau_sac ms ON ms.ma_mau = msp.ma_mau
    ";
					}
					$fromClause .= " LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp";

					$whereSQL = "";
					if (count($whereParts) > 0) {
						$whereSQL = " WHERE " . implode(" AND ", $whereParts);
					}

					// Pagination
					$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
					$perPage = 9; // số sản phẩm / trang
					$offset = ($page - 1) * $perPage;

					// Query sản phẩm (DISTINCT vì có thể join nhiều màu)
					$sqlProducts = "
    SELECT sp.ma_sp, sp.ten_sp, sp.gia, sp.ma_loaisp,
           MIN(img.ten_anh) AS ten_anh
    $fromClause
    $whereSQL
    GROUP BY sp.ma_sp, sp.ten_sp, sp.gia, sp.ma_loaisp
    $orderBy
    LIMIT $offset, $perPage
";
					$products = mysqli_query($conn, $sqlProducts);

					// Đếm tổng để làm phân trang
					$sqlCount = "
    SELECT COUNT(DISTINCT sp.ma_sp) AS total
    $fromClause
    $whereSQL
";
					$rsCount = mysqli_query($conn, $sqlCount);
					$rowCount = $rsCount ? mysqli_fetch_assoc($rsCount) : ['total' => 0];
					$totalItems = (int) $rowCount['total'];
					$totalPages = ($perPage > 0) ? ceil($totalItems / $perPage) : 1;

					// Lấy danh sách màu để hiển thị filter
					$colors = [];
					$sqlColorList = "SELECT ten_mau FROM mau_sac ORDER BY ten_mau ASC";
					$rsColorList = mysqli_query($conn, $sqlColorList);
					if ($rsColorList) {
						while ($row = mysqli_fetch_assoc($rsColorList)) {
							$colors[] = $row['ten_mau'];
						}
					}

					// Lấy wishlist 1 lần để check icon tim
					$wishlist = $_SESSION['wishlist'] ?? [];
					?>

					<!-- Product -->
					<div class="bg0 m-t-23 p-b-140">
						<div class="container">
							<div class="flex-w flex-sb-m p-b-52">
								<!-- Filter theo loại sản phẩm (danh mục) -->
								<div class="flex-w flex-l-m filter-tope-group m-tb-10">
									<a href="?category=all"
										class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?php echo ($cat_active == 'all' ? 'how-active1' : ''); ?>">
										Tất cả sản phẩm
									</a>

									<?php foreach ($categories as $dm):
										$isActive = ($cat_active == $dm['ma_loaisp']) ? 'how-active1' : '';
										?>
										<a href="?category=<?php echo (int) $dm['ma_loaisp']; ?>"
											class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?php echo $isActive; ?>">
											<?php echo htmlspecialchars($dm['ten_loai']); ?>
										</a>
									<?php endforeach; ?>
								</div>

								<div class="flex-w flex-c-m m-tb-10">
									<div
										class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
										<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
										<i
											class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
										Bộ lọc
									</div>

									<?php if ($sort !== 'default' || $price_from !== null || $price_to !== null || $color !== null): ?>
										<?php
										$paramsClear = $_GET;
										unset($paramsClear['sort'], $paramsClear['price_from'], $paramsClear['price_to'], $paramsClear['color'], $paramsClear['page']);
										$urlClear = '?' . http_build_query($paramsClear);
										?>
										<a href="<?php echo $urlClear; ?>"
											class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 text-danger font-weight-bold">
											<i class="zmdi zmdi-close m-r-6"></i>
											Bỏ lọc
										</a>
									<?php endif; ?>

									<div
										class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
										<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
										<i
											class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
										Tìm kiếm
									</div>
								</div>

								<!-- Search product -->
								<div class="dis-none panel-search w-full p-t-10 p-b-15">
									<form class="bor8 dis-flex p-l-15" method="get" action="product.php">
										<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
											<i class="zmdi zmdi-search"></i>
										</button>

										<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="q"
											placeholder="Tìm kiếm"
											value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
									</form>
								</div>


								<!-- PANEL FILTER (SẮP XẾP, GIÁ, MÀU) -->
								<div class="dis-none panel-filter w-full p-t-10">
									<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
										<!-- Sort -->
										<div class="filter-col1 p-r-15 p-b-27">
											<div class="mtext-102 cl2 p-b-15">Sắp xếp theo</div>
											<ul>
												<?php
												$filters = [
													'default' => 'Mặc định',
													'popular' => 'Phổ biến',
													'rating' => 'Đánh giá trung bình',
													'newest' => 'Mới nhất',
													'price-asc' => 'Giá: Thấp → Cao',
													'price-desc' => 'Giá: Cao → Thấp'
												];
												foreach ($filters as $key => $label) {
													$active = ($sort == $key) ? 'filter-link-active' : '';
													$params = $_GET;
													unset($params['page']); // RESET PAGE
													if ($sort == $key) {
														unset($params['sort']);
													} else {
														$params['sort'] = $key;
													}
													$url = '?' . http_build_query($params);
													$activeIcon = ($sort == $key) ? ' <i class="zmdi zmdi-close-circle-o m-l-4 text-danger"></i>' : '';
													echo '<li class="p-b-6">
										<a href="' . $url . '" class="filter-link stext-106 trans-04 ' . $active . '">' . $label . $activeIcon . '</a>
									</li>';
												}
												?>
											</ul>
										</div>

										<!-- Price -->
										<div class="filter-col2 p-r-15 p-b-27">
											<div class="mtext-102 cl2 p-b-15">Giá</div>
											<ul>
												<?php
												// Lấy giá max để chia khoảng
												$resMax = mysqli_query($conn, "SELECT MAX(gia) AS max_gia FROM san_pham WHERE ton_tai = b'1'");
												$rowMax = $resMax ? mysqli_fetch_assoc($resMax) : ['max_gia' => 0];
												$max_gia = (int) $rowMax['max_gia'];
												$step = 100000; // 100k 1 khoảng
												
												// Tất cả
												$paramsAll = $_GET;
												unset($paramsAll['price_from'], $paramsAll['price_to'], $paramsAll['page']);
												$urlAll = '?' . http_build_query($paramsAll);
												$activeAll = (!$price_from && !$price_to) ? 'filter-link-active' : '';
												echo '<li class="p-b-6"><a href="' . $urlAll . '" class="filter-link stext-106 trans-04 ' . $activeAll . '">Tất cả</a></li>';

												for ($from = 0; $from < $max_gia; $from += $step) {
													$to = $from + $step;
													if ($to >= $max_gia)
														$to = null;

													$paramsPrice = $_GET;
													unset($paramsPrice['page']); // RESET PAGE
													
													$isActive = ($price_from === $from && (($price_to === $to) || ($to === null && !$price_to)));
													if ($isActive) {
														unset($paramsPrice['price_from'], $paramsPrice['price_to']);
													} else {
														$paramsPrice['price_from'] = $from;
														if ($to)
															$paramsPrice['price_to'] = $to;
														else
															unset($paramsPrice['price_to']);
													}
													$urlP = '?' . http_build_query($paramsPrice);

													$activeClass = $isActive ? 'filter-link-active' : '';
													$activeIcon = $isActive ? ' <i class="zmdi zmdi-close-circle-o m-l-4 text-danger"></i>' : '';

													echo '<li class="p-b-6"><a href="' . $urlP . '" class="filter-link stext-106 trans-04 ' . $activeClass . '">';
													if ($to) {
														echo number_format($from) . "₫ - " . number_format($to) . "₫";
													} else {
														echo "Trên " . number_format($from) . "₫";
													}
													echo $activeIcon . '</a></li>';
												}
												?>
											</ul>
										</div>

										<!-- Color -->
										<div class="filter-col3 p-r-15 p-b-27">
											<div class="mtext-102 cl2 p-b-15">Màu sắc</div>
											<ul>
												<?php foreach ($colors as $c):
													$paramsColor = $_GET;
													unset($paramsColor['page']); // RESET PAGE
													
													$isActiveC = ($color === $c);
													if ($isActiveC) {
														unset($paramsColor['color']);
													} else {
														$paramsColor['color'] = $c;
													}
													$urlC = '?' . http_build_query($paramsColor);
													$activeC = $isActiveC ? 'filter-link-active' : '';
													$activeIcon = $isActiveC ? ' <i class="zmdi zmdi-close-circle-o m-l-4 text-danger"></i>' : '';
													?>
													<li class="p-b-6">
														<a href="<?php echo $urlC; ?>"
															class="filter-link color-filter-link stext-106 trans-04 d-inline-flex align-items-center gap-2 <?php echo $activeC; ?>">
															<span class="color-dot" style="background-color: <?php echo getColorHex($c); ?>; <?php echo (mb_strtolower(trim($c), 'UTF-8') == 'trắng') ? 'border: 1px solid #cbd5e1;' : ''; ?>"></span>
															<span><?php echo htmlspecialchars($c); ?></span>
															<?php echo $activeIcon; ?>
														</a>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>

									</div>
								</div>
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
										<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item"
											data-price="<?php echo (int) $row['gia']; ?>">

											<div class="block2">
												<div class="block2-pic hov-img0">
													<img src="images/<?php echo htmlspecialchars($imgFile); ?>"
														alt="IMG-PRODUCT">


													<a href="javascript:void(0);"
														class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
														data-id="<?php echo (int) $row['ma_sp']; ?>">
														Xem nhanh
													</a>
												</div>

												<div class="block2-txt flex-w flex-t p-t-14">
													<div class="block2-txt-child1 flex-col-l ">
														<a href="product-detail.php?id=<?php echo (int) $row['ma_sp']; ?>"
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
														<a href="wishlist_action.php?id=<?php echo (int) $row['ma_sp']; ?>"
															class="btn-addwish-b2 dis-block pos-relative <?php echo $isLiked ? 'js-addedwish-b2' : ''; ?>">
															<img class="icon-heart1 dis-block trans-04"
																src="images/icons/icon-heart-01.png" alt="ICON">
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
							<div class="flex-c-m flex-w w-full p-t-45">
								<?php if ($totalPages > 1): ?>
									<?php for ($i = 1; $i <= $totalPages; $i++): ?>
										<a href="<?= $urlPage ?>"
											class="flex-c-m how-pagination1 trans-04 m-all-7 <?= $active ?>">
											<?= $i ?>
										</a>
									<?php endfor; ?>
								<?php endif; ?>
							</div>

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
												<input class="input1 bg-none plh1 stext-107 cl7" type="text"
													name="email" placeholder="email@example.com">
												<div class="focus-input1 trans-04"></div>
											</div>

											<div class="p-t-18">
												<button
													class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
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
										<script>document.write(new Date().getFullYear());</script> Mọi quyền được bảo
										lưu | Giao
										diện được thiết kế
										bằng <i class="fa fa-heart-o" aria-hidden="true"></i> bởi <a
											href="https://colorlib.com" target="_blank">Colorlib</a>
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

										//xoá class tim xanh trong danh sách sản phẩm
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
							});
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
							$('.gallery-lb').each(function () {
								$(this).magnificPopup({
									delegate: 'a',
									type: 'image',
									gallery: { enabled: true },
									mainClass: 'mfp-fade'
								});
							});
						</script>
						<!--===============================================================================================-->
						<script src="vendor/isotope/isotope.pkgd.min.js"></script>
						<!--===============================================================================================-->
						<script src="vendor/sweetalert/sweetalert.min.js"></script>
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

							// Ngăn submit mặc định
							$('.js-addwish-b2, .js-addwish-detail').on('click', function (e) {
							});

							// Thả tim ở list sản phẩm
							$('.js-addwish-b2').each(function () {
								var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
								$(this).on('click', function () {
									swal(nameProduct, "đã được thêm vào danh sách yêu thích!", "success");

									$(this).addClass('js-addedwish-b2');
									$(this).off('click');
								});
							});

							// Thả tim trong popup chi tiết
							$('.js-addwish-detail').each(function () {
								var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

								$(this).on('click', function () {
									swal(nameProduct, "đã được thêm vào danh sách yêu thích!", "success");

									$(this).addClass('js-addedwish-detail');
									$(this).off('click');
								});
							});

							// Thêm vào giỏ trong popup chi tiết (chỉ hiện thông báo, phần AJAX bạn đã có bên dưới)
							$('.js-addcart-detail').each(function () {
								var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
								$(this).on('click', function () {
									swal(nameProduct, "đã được thêm vào giỏ hàng!", "success");
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
								});
							});
						</script>
						<!--===============================================================================================-->
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
				</body>

				</html>