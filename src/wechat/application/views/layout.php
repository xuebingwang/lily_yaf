<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="format-detection" content="telephone=no"/>
    <title><?=isset($meta_title) ? $meta_title.' - ' : ''?>百合花</title>
    <meta name="Author" Content="" />
    <meta name="Copyright" Content="深圳麦圈互动科技。All Rights Reserved" />
    <link href="/style/reset.css" rel="stylesheet" type="text/css" />
    <link href="/style/main.css?<?=md5_file('style/main.css')?>" rel="stylesheet" type="text/css" />
    <block name="style"></block>
</head>

<body>
<article class="index padt8">

    <block name="header">
    </block>
    <?php echo $content?>
    <footer class="footer">
        <a href="index.html" class="link on">
            <i class="nav nav1"></i>
            <p>首页</p>
        </a>
        <a href="reviews.html" class="link">
            <i class="nav nav2"></i>
            <p>名师点评</p>
        </a>
        <a href="my.html" class="link">
            <i class="nav nav3"></i>
            <p>我的</p>
        </a>
    </footer>

</article>

<script type="text/javascript" src="/js/framwork.js"></script>
<script type="text/javascript" src="/js/noclickdelay.js"></script>
<script type="text/javascript" src="/js/common.js?<?=md5_file('js/common.js')?>"></script>
<block name="script">

</block>
</body>
</html>
