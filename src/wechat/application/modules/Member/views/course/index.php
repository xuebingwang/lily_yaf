<div class="wrap padb80" id="item-wrap">
    <?php
    if(empty($list)):
        ?>
        <div class="weui_msg">
            <div class="weui_icon_area"><i class="weui_icon_msg weui_icon_info"></i></div>
            <div class="weui_text_area">
                <h2 class="weui_msg_title">提示</h2>
                <p class="weui_msg_desc">您还没有课件，点击下面的添加按钮上传！</p>
            </div>
        </div>
        <?php
    else:
        require_once('ajax.list.php');
    endif;
    ?>
    <input type="hidden" id="page" value="<?=$page?>" />
    <input type="hidden" id="surplus" value="<?=$total-count($list)?>" />
</div>
<footer class="footer">
    <a href="<?=U('/member/course/add')?>" class="add-plan">
        <i class="plus"></i>
        添加新的课件
    </a>
</footer>
<?php require_once('script.php') ?>