<!DOCTYPE html>
<?php
require 'db.php'; // Kết nối CSDL

// --- BẮT ĐẦU PHẦN XỬ LÝ AJAX ---
// Nếu có yêu cầu lấy thông tin sản phẩm từ Ajax gửi lên
if (isset($_POST['lay_thong_tin_sp_ajax']) && isset($_POST['ma_sp'])) {
    $ma_sp = $_POST['ma_sp'];

    // 1. Truy vấn sản phẩm
    $stmt = $pdo->prepare("SELECT * FROM san_pham WHERE ma_sp = :ma_sp");
    $stmt->execute(['ma_sp' => $ma_sp]);
    $p = $stmt->fetch();

    if ($p) {
        // 2. Truy vấn Size
        $stmt_size = $pdo->prepare("SELECT kc.ten_kichco FROM kich_co kc JOIN kichco_sp ks ON kc.ma_kichco = ks.ma_kichco WHERE ks.ma_sp = :ma_sp");
        $stmt_size->execute(['ma_sp' => $ma_sp]);
        $kich_co = $stmt_size->fetchAll();

        // 3. Truy vấn Màu
        $stmt_mau = $pdo->prepare("SELECT ms.ten_mau FROM mau_sac ms JOIN mau_sp msp ON ms.ma_mau = msp.ma_mau WHERE msp.ma_sp = :ma_sp");
        $stmt_mau->execute(['ma_sp' => $ma_sp]);
        $mau_sac = $stmt_mau->fetchAll();
        ?>
        
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
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-5 p-b-30">
                <div class="p-r-50 p-t-5 p-lr-0-lg">
                    <h4 class="mtext-105 cl2 js-name-detail p-b-14"><?= $p['ten_sp']; ?></h4>
                    <span class="mtext-106 cl2"><?php echo number_format($p['gia_sp'], 0, ',', '.'); ?> ₫</span>
                    <p class="stext-102 cl3 p-t-23"><?= $p['mota']; ?></p>
                    
                    <div class="p-t-33">
                        <div class="flex-w flex-r-m p-b-10">
                            <div class="size-203 flex-c-m respon6">Kích cỡ</div>
                            <div class="size-204 respon6-next">
                                <div class="rs1-select2 bor8 bg0">
                                    <select class="js-select2-modal" name="size">
                                        <option>Lựa chọn</option>
                                        <?php foreach ($kich_co as $kc) { echo "<option>{$kc['ten_kichco']}</option>"; } ?>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-w flex-r-m p-b-10">
                            <div class="size-203 flex-c-m respon6">Màu sắc</div>
                            <div class="size-204 respon6-next">
                                <div class="rs1-select2 bor8 bg0">
                                    <select class="js-select2-modal" name="color">
                                        <option>Lựa chọn</option>
                                        <?php foreach ($mau_sac as $ms) { echo "<option>{$ms['ten_mau']}</option>"; } ?>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-w flex-r-m p-b-10">
                            <div class="size-204 flex-w flex-m respon6-next">
                                <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                    <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"><i class="fs-16 zmdi zmdi-minus"></i></div>
                                    <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">
                                    <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"><i class="fs-16 zmdi zmdi-plus"></i></div>
                                </div>
                                <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">Thêm vào giỏ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    exit();
}
?>

<?php
    $sl = "SELECT * FROM san_pham";
    $stmt = $pdo->query($sl); 
    $san_pham = $stmt->fetchAll();
?>


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

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							Tài khoản
						</a>

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
					<a href="#" class="logo">
						<img src="images/icons/logo-01.png" alt="IMG-LOGO">
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li>
								<a href="index.html">Trang chủ</a>
							</li>

							<li class="active-menu">
								<a href="product.html">Sản phẩm</a>
							</li>

							<li class="label1" data-label1="hot">
								<a href="shoping-cart.html">Khuyến mãi</a>
							</li>

							<li>
								<a href="blog.html">Blog</a>
							</li>

							<li>
								<a href="about.html">Giới thiệu</a>
							</li>

							<li>
								<a href="contact.html">Liên hệ</a>
							</li>
						</ul>
					</div>	

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart" data-notify="2">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>

						<a href="#" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti" data-notify="0">
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
				<a href="index.html"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" data-notify="2">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti" data-notify="0">
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

						<a href="#" class="flex-c-m p-lr-10 trans-04">
							Tài khoản
						</a>

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
					<a href="index.html">Trang chủ</a>
					<ul class="sub-menu-m">
						<li><a href="index.html">Trang chủ 1</a></li>
						<li><a href="home-02.html">Trang chủ 2</a></li>
						<li><a href="home-03.html">Trang chủ 3</a></li>
					</ul>
					<span class="arrow-main-menu-m">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</li>

				<li>
					<a href="product.html">Sản phẩm</a>
				</li>

				<li>
					<a href="shoping-cart.html" class="label1 rs1" data-label1="hot">Khuyến mãi</a>
				</li>

				<li>
					<a href="blog.html">Blog</a>
				</li>

				<li>
					<a href="about.html">Giới thiệu</a>
				</li>

				<li>
					<a href="contact.html">Liên hệ</a>
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

	<!-- Cart -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>

		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Giỏ hàng của bạn
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>
			
			<div class="header-cart-content flex-w js-pscroll">
				<ul class="header-cart-wrapitem w-full">
					<li class="header-cart-item flex-w flex-t m-b-12">
						<div class="header-cart-item-img">
							<img src="images/item-cart-01.jpg" alt="IMG">
						</div>

						<div class="header-cart-item-txt p-t-8">
							<a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
								Áo sơ mi trắng
							</a>

							<span class="header-cart-item-info">
								1 x 437.000₫
							</span>
						</div>
					</li>

					<li class="header-cart-item flex-w flex-t m-b-12">
						<div class="header-cart-item-img">
							<img src="images/item-cart-02.jpg" alt="IMG">
						</div>

						<div class="header-cart-item-txt p-t-8">
							<a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
								Giày Converse All Star Hi
							</a>

							<span class="header-cart-item-info">
								1 x 897.000₫
							</span>
						</div>
					</li>

					<li class="header-cart-item flex-w flex-t m-b-12">
						<div class="header-cart-item-img">
							<img src="images/item-cart-03.jpg" alt="IMG">
						</div>

						<div class="header-cart-item-txt p-t-8">
							<a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
								Đồng hồ Nixon Porter
							</a>

							<span class="header-cart-item-info">
								1 x 391.000₫
							</span>
						</div>
					</li>
				</ul>
				
				<div class="w-full">
					<div class="header-cart-total w-full p-tb-40">
						Tổng cộng: 1.725.000₫
					</div>

					<div class="header-cart-buttons flex-w w-full">
						<a href="shoping-cart.html" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							Xem giỏ hàng
						</a>

						<a href="shoping-cart.html" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Thanh toán
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- THÔNG TIN SẢN PHẨM Ở ĐÂY -->
	<!-- Product -->
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52">
				<!-- TỪ ĐÂY -->
					<?php 
					// 1. Lấy danh mục hiện tại từ URL (mặc định là 'all')
					$cat_active = isset($_GET['category']) ? $_GET['category'] : 'all'; 

					// 2. Truy vấn lấy danh sách danh mục từ Database
					// Giả sử bảng tên là 'loai_sp', sắp xếp theo tên hoặc ID
					$stmt_cat = $pdo->prepare("SELECT * FROM loai_sp ORDER BY ma_loaisp ASC");
					$stmt_cat->execute();
					$danh_muc = $stmt_cat->fetchAll();
					?>
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<a href="?category=all" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?= $cat_active=='all'?'how-active1':'' ?>">
						Tất cả sản phẩm
					</a>

					<?php foreach ($danh_muc as $dm): 
						$isActive = ($cat_active == $dm['ma_loaisp']) ? 'how-active1' : '';
					?>
						<a href="?category=<?= $dm['ma_loaisp'] ?>"
						class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 <?= $isActive ?>">
							<?= $dm['ten_loai'] ?>
						</a>
					<?php endforeach; ?>
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

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Tìm kiếm">
					</div>	
				</div>

				<?php
				// ----------------- XỬ LÝ FILTER -----------------
				$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
				$price_from = isset($_GET['price_from']) ? (int)$_GET['price_from'] : null;
				$price_to = isset($_GET['price_to']) ? (int)$_GET['price_to'] : null;
				$color = isset($_GET['color']) ? $_GET['color'] : null;
				// $tag = isset($_GET['tag']) ? $_GET['tag'] : null;

				// ORDER BY
				switch ($sort) {
					case 'newest': $orderBy = "ORDER BY ngay_tao DESC"; break;
					case 'price-asc': $orderBy = "ORDER BY gia_sp ASC"; break;
					case 'price-desc': $orderBy = "ORDER BY gia_sp DESC"; break;
					default: $orderBy = "";
				}

				// Build WHERE condition
				$where = [];
				$params = [];

				if ($price_from !== null) {
					$where[] = "gia_sp >= :price_from";
					$params[':price_from'] = $price_from;
				}
				if ($price_to !== null) {
					$where[] = "gia_sp <= :price_to";
					$params[':price_to'] = $price_to;
				}
				if ($color) {
					$where[] = "mau_sac = :color";
					$params[':color'] = $color;
				}
				if ($cat_active != 'all') {
					$where[] = "ma_loaisp = :ma_loaisp";
					$params[':ma_loaisp'] = $cat_active;
				}
				// if ($tag) {
				// 	// Nếu tag lưu trong 1 bảng phụ thì JOIN, ví dụ đây đơn giản filter tên tag
				// 	$where[] = "tags LIKE :tag";
				// 	$params[':tag'] = "%$tag%";
				// }

				$whereSQL = "";
				if (count($where) > 0) {
					$whereSQL = "WHERE " . implode(" AND ", $where);
				}

				// --- Pagination ---
				$page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
				$perPage = 6; // số sản phẩm / trang
				$offset = ($page-1)*$perPage;

				// --- Query sản phẩm ---
				$sql = "SELECT * FROM san_pham $whereSQL $orderBy LIMIT :offset, :perPage";
				$stmt = $pdo->prepare($sql);
				foreach($params as $k=>$v) $stmt->bindValue($k, $v);
				$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
				$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
				$stmt->execute();
				$san_pham = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// --- Lấy tổng sản phẩm để tính pagination ---
				$countSQL = "SELECT COUNT(*) FROM san_pham $whereSQL";
				$stmtCount = $pdo->prepare($countSQL);
				foreach($params as $k=>$v) $stmtCount->bindValue($k, $v);
				$stmtCount->execute();
				$totalItems = $stmtCount->fetchColumn();
				$totalPages = ceil($totalItems / $perPage);
				?>

				<!-- ----------------- FILTER PANEL ----------------- -->
				<div class="panel-filter w-full p-t-10" style="display:none;">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">Sắp xếp theo</div>
							<ul>
								<?php
								$filters = [
									'default'=>'Mặc định',
									'popular'=>'Phổ biến',
									'rating'=>'Đánh giá trung bình',
									'newest'=>'Mới nhất',
									'price-asc'=>'Giá: Thấp → Cao',
									'price-desc'=>'Giá: Cao → Thấp'
								];
								foreach($filters as $key => $label){
									$active = ($sort == $key) ? 'filter-link-active' : '';
									echo '<li class="p-b-6"><a href="?sort='.$key.'" class="filter-link stext-106 trans-04 '.$active.'">'.$label.'</a></li>';
								}
								?>
							</ul>
						</div>
						<!-- Lọc theo giá -->
						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">Giá</div>
							<ul>
								<li class="p-b-6">
									<a href="?sort=<?= $sort ?>" class="filter-link stext-106 trans-04 <?= (!$price_from && !$price_to)?'filter-link-active':'' ?>">Tất cả</a>
								</li>
								<?php
								$buoc_gia = 100000;
								$sl_gia = "SELECT MAX(gia_sp) AS max_gia FROM san_pham";
								$max_gia = $pdo->query($sl_gia)->fetch(PDO::FETCH_ASSOC)['max_gia'];
								for($from=0; $from<$max_gia; $from+=$buoc_gia){
									$to = $from + $buoc_gia;
									if($to >= $max_gia) $to=null;
									$active = ($price_from==$from && $price_to==$to)?'filter-link-active':'';
									echo '<li class="p-b-6"><a href="?sort='.$sort.'&price_from='.$from.($to?'&price_to='.$to:'').'" class="filter-link stext-106 trans-04 '.$active.'">';
									echo $to? number_format($from)."₫ - ".number_format($to)."₫" : "Trên ".number_format($from)."₫";
									echo '</a></li>';
								}
								?>
							</ul>
						</div>
						<!-- Lọc theo màu -->
						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">Màu sắc</div>
							<ul>
								<?php
								$mau_sac = $pdo->query("SELECT * FROM mau_sac ORDER BY ten_mau ASC")->fetchAll();
								foreach($mau_sac as $ms){
									$active = ($color==$ms['ten_mau'])?'filter-link-active':'';
									echo '<li class="p-b-6"><a href="?sort='.$sort.'&color='.$ms['ten_mau'].'" class="filter-link stext-106 trans-04 '.$active.'">'.$ms['ten_mau'].'</a></li>';
								}
								?>
							</ul>
						</div>
					</div>
				</div>

				<!-- TỚI ĐÂY -->

				<div class="row ">
					<!-- Chèn PHP vô đây -->

					<?php foreach ($san_pham as $p) { ?>
						<!-- THÔNG TIN 1 SP -->
						<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 ">
							<!-- Block2 -->
							<div class="block2">
								<div class="block2-pic hov-img0">
									<img src="images/product-01.jpg" alt="IMG-PRODUCT">

									<a href="#" 
									class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 js-show-modal1" 
									data-id="<?= $p['ma_sp'] ?>">
									Xem nhanh
									</a>

								</div>

								<div class="block2-txt flex-w flex-t p-t-14">
									<div class="block2-txt-child1 flex-col-l ">
										<a href="product-detail.php?id=<?= $p['ma_sp']; ?>"  class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
											<?= $p['ten_sp']; ?>
										</a>

										<span class="stext-105 cl3">
											<?php echo number_format($p['gia_sp'], 0, ',', '.'); ?>
									vnd
										</span>
									</div>

									<div class="block2-txt-child2 flex-r p-t-3">
										<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
											<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
											<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- Load more -->
					<div class="flex-c-m flex-w w-full p-t-45">
						<?php
						// Giả sử $totalPages đã tính trước đó
						if ($totalPages > 1) {
							echo '<p>Trang: ';
							for ($i = 1; $i <= $totalPages; $i++) {
								// Nếu trang hiện tại
								if ($page == $i) {
									echo "<span class='pnow'>$i</span> ";
								} else {
									// Giữ các filter/loại hiện tại nếu có
									$urlParams = $_GET;
									$urlParams['page'] = $i;
									$url = '?' . http_build_query($urlParams);
									echo "<a href='$url'>$i</a> ";
								}
							}
							echo '</p>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

	<!-- Modal1 -->
	 <!-- ĐÂY LÀ MODAL XEM NHANH SẢN PHẨM -->
	<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
    <div class="overlay-modal1 js-hide-modal1"></div>
    <div class="container">
        <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
            <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                <img src="images/icons/icon-close.png" alt="CLOSE">
            </button>

            <div id="modal-content-loader"></div>
            
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
		$('.js-addwish-b2, .js-addwish-detail').on('click', function(e){
			e.preventDefault();
		});

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
	<script src="js/quickview.js"></script>
</body>
</html>

<script>
    $(document).ready(function() {
        // 1. Chọn vùng chứa sản phẩm
        var $grid = $('.isotope-grid');

        // 2. Khởi tạo Isotope
        $grid.isotope({
            itemSelector: '.isotope-item',
            layoutMode: 'fitRows', // Hoặc 'masonry' nếu muốn so le
            percentPosition: true,
            filter: '*' // QUAN TRỌNG: Hiển thị tất cả (vì PHP đã lọc dữ liệu rồi)
        });

        // 3. Fix lỗi chồng hình: Đợi ảnh load xong thì layout lại lần nữa
        // Yêu cầu template phải có thư viện imagesLoaded (CozaStore mặc định có)
        if (typeof($.fn.imagesLoaded) !== 'undefined') {
             $grid.imagesLoaded().progress( function() {
                $grid.isotope('layout');
            });
        }
        
        // 4. (Tùy chọn) Force layout sau 1 khoảng thời gian ngắn để chắc chắn
        setTimeout(function(){
            $grid.isotope('layout');
        }, 500);
    });
</script>