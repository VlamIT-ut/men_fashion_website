<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if (!isset($conn)) {
    echo "Lỗi kết nối CSDL";
    exit;
}

if (!isset($_POST['lay_thong_tin_sp_ajax']) || !isset($_POST['ma_sp'])) {
    exit;
}

$ma_sp = (int) $_POST['ma_sp'];

$sql = "SELECT sp.*, MIN(img.ten_anh) as ten_anh 
        FROM san_pham sp 
        LEFT JOIN hinhanh_sp img ON sp.ma_sp = img.ma_sp 
        WHERE sp.ma_sp = $ma_sp 
        GROUP BY sp.ma_sp";

$rs = mysqli_query($conn, $sql);
if (!$rs || mysqli_num_rows($rs) == 0) {
    echo "<p class='text-center p-t-50'>Không tìm thấy sản phẩm (ID: $ma_sp)</p>";
    exit;
}
$p = mysqli_fetch_assoc($rs);

$sqlImg = "SELECT ten_anh FROM hinhanh_sp WHERE ma_sp = $ma_sp";
$rsImg = mysqli_query($conn, $sqlImg);
$images = [];
while ($row = mysqli_fetch_assoc($rsImg)) {
    $images[] = $row['ten_anh'];
}
if (empty($images)) {
    $images[] = $p['ten_anh'] ?? 'product-01.jpg';
}

$sizes = [];
$sql_size = "SELECT kc.ten_kichco FROM kich_co kc JOIN kichco_sp ks ON kc.ma_kichco = ks.ma_kichco WHERE ks.ma_sp = $ma_sp";
$rs_size = mysqli_query($conn, $sql_size);
if ($rs_size) {
    while ($row = mysqli_fetch_assoc($rs_size)) {
        $sizes[] = $row['ten_kichco'];
    }
}
if (empty($sizes)) $sizes = ['S', 'M', 'L', 'XL'];

$colors = [];
$sql_color = "SELECT ms.ten_mau FROM mau_sac ms JOIN mau_sp msp ON ms.ma_mau = msp.ma_mau WHERE msp.ma_sp = $ma_sp";
$rs_color = mysqli_query($conn, $sql_color);
if ($rs_color) {
    while ($row = mysqli_fetch_assoc($rs_color)) {
        $colors[] = $row['ten_mau'];
    }
}

function getColorHex($colorName) {
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

            <span class="mtext-106 cl2 text-danger font-weight-bold fs-20">
                <?php echo ($p['gia'] !== null) ? number_format($p['gia'], 0, ',', '.') . "₫" : "Liên hệ"; ?>
            </span>

            <p class="stext-102 cl3 p-t-23">
                <?php echo !empty($p['mota']) ? nl2br(htmlspecialchars($p['mota'])) : "Sản phẩm chưa có mô tả."; ?>
            </p>

            <div class="p-t-33">
                <!-- Lựa chọn Size -->
                <div class="flex-w flex-r-m p-b-15">
                    <div class="size-203 flex-c-m respon6 text-dark font-weight-bold">
                        Kích cỡ
                    </div>
                    <div class="size-204 respon6-next flex-w gap-2">
                        <?php foreach ($sizes as $idx => $kc): ?>
                            <label class="custom-radio-btn mb-0">
                                <input type="radio" name="size" value="<?php echo htmlspecialchars($kc); ?>" <?php echo $idx === 0 ? 'checked' : ''; ?> class="d-none">
                                <span class="size-pill pointer trans-04"><?php echo htmlspecialchars($kc); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lựa chọn Màu sắc -->
                <div class="flex-w flex-r-m p-b-15">
                    <div class="size-203 flex-c-m respon6 text-dark font-weight-bold">
                        Màu sắc
                    </div>
                    <div class="size-204 respon6-next flex-w gap-2">
                        <?php if (empty($colors)): ?>
                            <span class="stext-102 cl6 p-t-5">Mặc định</span>
                        <?php else: ?>
                            <?php foreach ($colors as $idx => $mau): ?>
                                <label class="custom-radio-btn mb-0 tooltip100" data-tooltip="<?php echo htmlspecialchars($mau); ?>">
                                    <input type="radio" name="color" value="<?php echo htmlspecialchars($mau); ?>" <?php echo $idx === 0 ? 'checked' : ''; ?> class="d-none">
                                    <span class="color-dot-radio pointer trans-04" style="background-color: <?php echo getColorHex($mau); ?>; <?php echo (mb_strtolower(trim($mau), 'UTF-8') == 'trắng') ? 'border: 1px solid #cbd5e1;' : ''; ?>"></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
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

                        <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail" data-id="<?php echo (int) $p['ma_sp']; ?>">
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                <div class="flex-m bor9 p-r-10 m-r-11">
                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
                        <i class="zmdi zmdi-favorite"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Giao diện nút Radio (Size & Color) */
.gap-2 { gap: 8px; }
.size-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    background: #f8fafc;
    transition: all 0.3s ease;
}
.custom-radio-btn input:checked + .size-pill {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    transform: translateY(-2px);
}
.size-pill:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.color-dot-radio {
    display: inline-block;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    position: relative;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.custom-radio-btn input:checked + .color-dot-radio {
    transform: scale(1.15) translateY(-2px);
    outline: 2px solid #4f46e5;
    outline-offset: 3px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.color-dot-radio:hover {
    transform: scale(1.1);
}
</style>
