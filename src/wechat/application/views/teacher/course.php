<header class="header">
    <a href="javascript:back(); " class="back"><i class="ico i-back"></i></a>
    <div class="txt">名师课件</div>
    <a href="javascript:;" class="search"><i class="ico i-search"></i></a>
</header>
<div class="wrap" id="item-wrap">
    <?php
    if(empty($list)):
        ?>
        <div class="weui_msg">
            <div class="weui_icon_area"><i class="weui_icon_msg weui_icon_info"></i></div>
            <div class="weui_text_area">
                <h2 class="weui_msg_title">提示</h2>
                <p class="weui_msg_desc">没有找到指定条件的数据！</p>
            </div>
        </div>
        <?php
    else:
        require_once('course.ajax.list.php');
    endif;
    ?>
    <input type="hidden" id="page" value="<?=$page?>" />
    <input type="hidden" id="surplus" value="<?=$total-count($list)?>" />
</div>
<div class="bgshadow"></div>
<div class="searchbox">
    <?php
    $name = I('keyword');
    ?>
    <div class="search_inpt">
        <form action="<?=U('/teacher/index')?>" method="get">
            <input type="text" value="<?= $name ?>" class="inp1" name="name" placeholder="请输入老师名称">
            <input type="submit" value="" class="ico sub">
        </form>
    </div>
</div>
<?php require_once 'script.php'?>