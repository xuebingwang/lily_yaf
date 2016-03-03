<div class="wrap padb80">
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
<footer class="footer">
    <a href="<?=U('/member/plan/add')?>" class="add-plan">
        <i class="plus"></i>
        添加新的商业计划书
    </a>
</footer>
<?php require_once 'script.php'?>