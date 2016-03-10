<div class="center teacher-bg ">
    <div class="ban1 swiper-container">
        <div class="swiper-wrapper">
            <?php  if(empty($album[0]['pic_url'])): ?>
                <div class="swiper-slide"> <img class="img" src="/images/p1.jpg"> </div>
            <?php else: ?>
                <?php foreach($album as $item):?>
                    <div class="swiper-slide"> <img class="img" src="<?=imageView2($item['pic_url'],375,170)?>"> </div>
                <?php endforeach;?>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="bar" style="z-index: 10;">
        <a href="#" id="showQr">
            <i class="ico i-wx"></i> 加微信
        </a>
        <a href="#" >
            <i class="ico i-class"></i> 名师课件
        </a>
    </div>
</div>
<div class="padm17">
    <div class="box teacher-info">
        <div class="tit">名师简介</div>
        <div class="name"><?= $teacher['name'] ?></div>
        <div class="good"><?= $teacher['good_at'] ?></div>
        <p><?= $teacher['description'] ?></p>
    </div>
</div>


<style>
    /**
 * Swiper 3.0.4
 * Most modern mobile touch slider and framework with hardware accelerated transitions
 *
 * http://www.idangero.us/swiper/
 *
 * Copyright 2015, Vladimir Kharlampidi
 * The iDangero.us
 * http://www.idangero.us/
 *
 * Licensed under MIT
 *
 * Released on: March 6, 2015
 */
    .swiper-slide,.swiper-wrapper{height:100%;position:relative;transform-style:preserve-3d;width:100%}
    .swiper-pagination,.swiper-wrapper{-webkit-transform:translate3d(0,0,0)}
    .swiper-container{margin:0 auto;position:relative;overflow:hidden;z-index:1}
    .swiper-container-vertical>.swiper-wrapper{-webkit-box-orient:vertical;-moz-box-orient:vertical;-ms-flex-direction:column;-webkit-flex-direction:column;flex-direction:column}
    .swiper-wrapper{z-index:1;display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:flex;-webkit-transition-property:-webkit-transform;-moz-transition-property:-moz-transform;-o-transition-property:-o-transform;-ms-transition-property:-ms-transform;transition-property:transform;-moz-transform:translate3d(0,0,0);-o-transform:translate(0,0);-ms-transform:translate3d(0,0,0);transform:translate3d(0,0,0);-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box}
    .swiper-slide,.swiper-wrapper{-ms-transform-style:preserve-3d;-moz-transform-style:preserve-3d;-webkit-transform-style:preserve-3d}
    .swiper-container-multirow>.swiper-wrapper{-webkit-box-lines:multiple;-moz-box-lines:multiple;-ms-fles-wrap:wrap;-webkit-flex-wrap:wrap;flex-wrap:wrap}
    .swiper-container-free-mode>.swiper-wrapper{-webkit-transition-timing-function:ease-out;-moz-transition-timing-function:ease-out;-ms-transition-timing-function:ease-out;-o-transition-timing-function:ease-out;transition-timing-function:ease-out;margin:0 auto}
    .swiper-slide{-webkit-flex-shrink:0;-ms-flex:0 0 auto;flex-shrink:0}
    .swiper-wp8-horizontal{-ms-touch-action:pan-y;touch-action:pan-y}
    .swiper-wp8-vertical{-ms-touch-action:pan-x;touch-action:pan-x}
    .swiper-pagination{position:absolute;text-align:center;-webkit-transition:300ms;-moz-transition:300ms;-o-transition:300ms;transition:300ms;-ms-transform:translate3d(0,0,0);-o-transform:translate3d(0,0,0);transform:translate3d(0,0,0);z-index:10}
    .swiper-pagination.swiper-pagination-hidden{opacity:0}
    .swiper-pagination-bullet{width:8px;height:8px;display:inline-block;border-radius:100%;background:#000;opacity:.2}
    .swiper-pagination-clickable .swiper-pagination-bullet{cursor:pointer}
    .swiper-pagination-white .swiper-pagination-bullet{background:#fff}
    .swiper-pagination-bullet-active{opacity:1;background:#007aff}
    .swiper-pagination-white .swiper-pagination-bullet-active{background:#fff}
    .swiper-pagination-black .swiper-pagination-bullet-active{background:#000}
    .swiper-container-vertical>.swiper-pagination{right:10px;top:50%;-webkit-transform:translate3d(0,-50%,0);-moz-transform:translate3d(0,-50%,0);-o-transform:translate(0,-50%);-ms-transform:translate3d(0,-50%,0);transform:translate3d(0,-50%,0)}
    .swiper-container-vertical>.swiper-pagination .swiper-pagination-bullet{margin:5px 0;display:block}
    .swiper-container-horizontal>.swiper-pagination{bottom:10px;left:0;width:100%}
    .swiper-container-horizontal>.swiper-pagination .swiper-pagination-bullet{margin:0 5px}
    .swiper-container{width:100%;-webkit-perspective:1200px;-moz-perspective:1200px;-ms-perspective:1200px;perspective:1200px}
</style>
<block name="script">
    <script src="/js/swiper.min.js"></script>
    <script type="text/javascript">
        $(function(){
            var mySwiper = new Swiper('.ban1',{
                loop: true,
                speed:800,
                autoplay: 3000,
                pagination: '.swiper-pagination',
                paginationClickable: true
            });
        });

        function showQrCode(cont) {
            if (cont === '未添加微信二维码') {
                layer.open({
                    content: cont
                })
            } else {
                layer.open({
                    content: '<img src="' + cont + '">'
                });
            }
        }
        $('#showQr').on('click', function () {
            showQrCode("<?= empty($teacher['qr_code_url']) ? "未添加微信二维码" : imageView2($teacher['qr_code_url'],200,200) ?>")
        });
    </script>
</block>