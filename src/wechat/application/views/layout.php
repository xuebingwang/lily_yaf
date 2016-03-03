<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="format-detection" content="telephone=no"/>
    <title><?=isset($meta_title) ? $meta_title.' - ' : ''?>百合花</title>
    <meta name="Author" Content="" />
    <meta name="Copyright" Content="深圳麦圈互动科技。All Rights Reserved" />
    <link href="/style/weui.css" rel="stylesheet" type="text/css" />
    <link href="/style/reset.css" rel="stylesheet" type="text/css" />
    <link href="/style/main.css?<?=md5_file('style/main.css')?>" rel="stylesheet" type="text/css" />
    <block name="style"></block>
</head>

<body>
<article class="index <?php if(isset($title)) echo 'padt8';?>">

<block name="header">
<?php if(isset($title)):?>
    <header class="header">
        <a href="<?=(isset($back_url) ? $back_url : 'javascript:back();')?>" class="back"><i class="ico i-back"></i></a>
        <div class="txt"><?=$title?></div>
    </header>
<?php endif;?>
</block>

    <?php echo $content?>

</article>

<script type="text/javascript" src="/js/framwork.js"></script>
<script type="text/javascript" src="/js/noclickdelay.js"></script>
<script type="text/javascript" src="/js/layer/layer.mobile.js"></script>
<script type="text/javascript" src="/js/common.js?<?=md5_file('js/common.js')?>"></script>
<block name="script">

</block>
</body>
</html>
