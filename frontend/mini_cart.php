<?php
// RẤT QUAN TRỌNG: phải có dòng này khi file được gọi riêng bằng AJAX
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = $_SESSION['cart'] ?? [];
$cartTotal = 0;
?>

<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        
        <!-- Title -->
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">GIỎ HÀNG CỦA BẠN</span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">

            <?php if (empty($cart)): ?>
                <p class="stext-110 cl2 p-l-5 p-t-20">Giỏ hàng đang trống.</p>
            <?php else: ?>

                <ul class="header-cart-wrapitem w-full">
                    <?php foreach ($cart as $item):
                        $id    = $item['id'];
                        $name  = $item['ten_sp'] ?? 'Sản phẩm';
                        $price = $item['gia'] ?? 0;
                        $qty   = $item['qty'] ?? 1;
                        $img   = $item['hinh'] ?? 'no-image.jpg';

                        $cartTotal += $price * $qty;
                    ?>
                        <li class="header-cart-item flex-w flex-t m-b-12" data-id="<?php echo $id; ?>">
                            <div class="header-cart-item-img">
                                <img src="images/products/<?php echo $img; ?>" alt="IMG">
                            </div>

                            <div class="header-cart-item-txt p-t-8">
                                <a href="product-detail.php?id=<?php echo $id; ?>"
                                   class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    <?php echo htmlspecialchars($name); ?>
                                </a>

                                <span class="header-cart-item-info">
                                    <span class="mini-qty"><?php echo $qty; ?></span> x
                                    <?php echo number_format($price, 0, ',', '.'); ?>₫
                                </span>

                                <div class="flex-w m-t-10">
                                    <button class="btn-mini-dec bor2 p-lr-10 m-r-5"
                                            data-id="<?php echo $id; ?>">−</button>

                                    <button class="btn-mini-inc bor2 p-lr-10 m-r-10"
                                            data-id="<?php echo $id; ?>">+</button>

                                    <button class="btn-mini-remove cl2 hov-cl1"
                                            data-id="<?php echo $id; ?>">
                                        <i class="zmdi zmdi-delete"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="header-cart-total w-full p-tb-30">
                    Tổng cộng: 
                    <span id="mini-cart-total">
                        <?php echo number_format($cartTotal, 0, ',', '.'); ?>₫
                    </span>
                </div>

            <?php endif; ?>

            <div class="header-cart-buttons flex-w w-full">
                <a href="shoping-cart.php"
                   class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 m-r-8">
                    Xem giỏ hàng
                </a>

                <a href="checkout.php"
                   class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15">
                    Thanh toán
                </a>
            </div>
        </div>
    </div>
</div>
