<header class="header">
    <a href="javascript:back(); " class="back"><i class="ico i-back"></i></a>
    <div class="txt">商业计划书展示</div>
    <a href="javascript:;" class="search"><i class="ico i-search"></i></a>
</header>
<div class="wrap">
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
        require_once('ajax.list.php');
    endif;
    ?>
</div>
<div class="bgshadow"></div>
<div class="searchbox">
    <?php
    $keyword    = I('keyword');
    $order      = I('order');
    $cate_id    = I('cate_id');
    ?>
    <div class="search_inpt">
        <form action="<?=U('/plan/index')?>" method="get">
            <input type="text" value="<?=$keyword?>" class="inp1" name="keyword" placeholder="请输入关键字">
            <input name="cate_id" type="hidden" value="<?=$cate_id?>">
            <input name="order" type="hidden" value="<?=$order?>">
            <input type="submit" value="" class="ico sub">
        </form>
    </div>
    <div class="tag">
        <a href="<?=U('/plan/index',['keyword'=>$keyword,'order'=>'like'])?>" <?php if($order == 'like') echo ' class="on"'?>>人气最高</a>
        <a href="<?=U('/plan/index',['keyword'=>$keyword,'order'=>'down'])?>" <?php if($order == 'down') echo ' class="on"'?>>下载最多</a>

        <?php foreach($cate_list as $cate):?>
        <a href="<?=U('/plan/index',['keyword'=>$keyword,'cate_id'=>$cate['id']])?>" <?php if($cate_id == $cate['id']) echo ' class="on"'?>><?=$cate['title']?></a>
        <?php endforeach;?>
    </div>
</div>
<?php require_once 'script.php'?>