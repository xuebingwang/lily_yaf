<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?=isset($meta_title) ? $meta_title.' - ' : ''?>百合花</title>
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="Author" Content="" />
    <meta name="Copyright" Content="深圳麦圈互动科技。All Rights Reserved" />

    <link href="/style/weui.css?<?=md5_file('style/weui.css')?>" rel="stylesheet" type="text/css" />
    <link href="/style/style.css" rel="stylesheet" type="text/css" />
    <block name="style"></block>
</head>

<body ontouchstart>
<block name="header">
</block>
<div class="container js_container">

</div>
<?php echo $content?>

<script type="text/javascript" src="/js/zepto.min.js"></script>
<script type="text/javascript" src="/js/example.js?<?=md5_file('js/example.js')?>"></script>
<block name="script">

</block>
</body>
</html>
