$(document).ready(function() {

    $(document).on('click', '.js-show-modal1', function(e) {
        e.preventDefault();
        var idSanPham = $(this).data('id');

        $('.js-modal1').addClass('show-modal1');
        $('#modal-content-loader').html('<div style="padding:50px; text-align:center;">Đang tải dữ liệu...</div>');

        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                lay_thong_tin_sp_ajax: true,
                ma_sp: idSanPham 
            },
            success: function(response) {
                $('#modal-content-loader').html(response);

                $(".js-select2-modal").each(function(){
                    $(this).select2({
                        minimumResultsForSearch: 20,
                        dropdownParent: $(this).next('.dropDownSelect2')
                    });
                });

                $('.wrap-slick3').each(function(){
                    var wrapSlick3 = $(this);
                    var slick3 = wrapSlick3.find('.slick3');
                    if(slick3.length > 0) {
                        slick3.slick({
                            slidesToShow: 1, slidesToScroll: 1, fade: true, infinite: true,
                            autoplay: false, autoplaySpeed: 6000, arrows: true,
                            appendArrows: wrapSlick3.find('.wrap-slick3-arrows'),
                            prevArrow:'<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                            nextArrow:'<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                            dots: true,
                            appendDots: wrapSlick3.find('.wrap-slick3-dots'),
                            dotsClass:'slick3-dots',
                            customPaging: function(slick, index) {
                                var portrait = $(slick.$slides[index]).data('thumb');
                                return '<img src=" ' + portrait + ' "/><div class="slick3-dot-overlay"></div>';
                            },
                        });
                    }
                });

                $('.btn-num-product-down').on('click', function(){
                    var numProduct = Number($(this).next().val());
                    if(numProduct > 0) $(this).next().val(numProduct - 1);
                });
                $('.btn-num-product-up').on('click', function(){
                    var numProduct = Number($(this).prev().val());
                    $(this).prev().val(numProduct + 1);
                });

                $('.js-addcart-detail').each(function(){
                    var nameProduct = $(this).closest('.product-detail').find('.js-name-detail').html();
                    $(this).on('click', function(){
                        swal(nameProduct, "đã được thêm vào giỏ hàng!", "success");
                    });
                });
            }
        });
    });

    $('.js-hide-modal1').on('click', function(){
        $('.js-modal1').removeClass('show-modal1');
    });

});