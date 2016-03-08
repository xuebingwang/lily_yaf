<div class="center"><img src="/images/p1.jpg" class="img"></div>
<div class="wrap padb90">
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
        require_once APP_PATH.'views/plan/ajax.list.php';
    endif;
    ?>
</div>
<?php require_once APP_PATH.'views/common/teacher.nav.php'?>
<?php require_once APP_PATH.'views/plan/script.php'?>