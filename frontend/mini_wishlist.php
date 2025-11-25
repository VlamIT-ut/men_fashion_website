<?php
// mini_wishlist.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$wishlist = $_SESSION['wishlist'] ?? [];
$count    = count($wishlist);
?>

<div class="wrap-header-cart js-panel-wishlist">
    <div class="s-full js-hide-wishlist"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">
                Sản phẩm yêu thích (<?php echo $count; ?>)
            </span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 js-hide-wishlist">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            <?php if ($count == 0): ?>
                <p class="stext-110 p-t-20">
                    Chưa có sản phẩm nào trong danh sách yêu thích.
                </p>
            <?php else: ?>
                <ul class="header-cart-wrapitem w-full">
                    <?php foreach ($wishlist as $item): ?>
                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <div class="header-cart-item-img">
                                <img src="images/<?php echo htmlspecialchars($item['ten_anh']); ?>" alt="IMG">
                            </div>

                            <div class="header-cart-item-txt p-t-8">
                                <a href="product-detail.php?id=<?php echo (int)$item['id']; ?>"
                                   class="header-cart-item-name m-b-5 hov-cl1 trans-04">
                                    <?php echo htmlspecialchars($item['ten_sp']); ?>
                                </a>

                                <span class="header-cart-item-info">
                                    <?php echo number_format($item['gia']); ?>₫
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
