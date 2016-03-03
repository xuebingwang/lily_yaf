<div class="wrap">
    <?php
    if(empty($list)):
    ?>
        <div class="weui_msg">
            <div class="weui_icon_area"><i class="weui_icon_msg weui_icon_info"></i></div>
            <div class="weui_text_area">
                <h2 class="weui_msg_title">提示</h2>
                <p class="weui_msg_desc">您还没有上传计划书，点击下面的添加按钮上传！</p>
            </div>
        </div>
    <?php
    else:
        require_once('ajax.list.php');
    endif;
    ?>
</div>
<?php require_once 'script.php'?>