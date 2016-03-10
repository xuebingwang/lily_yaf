<div class="wrap" >
    <div class="box list1 rewlist">
        <ul id="item-wrap">
            <?php
            require_once('comment.ajax.php');
            ?>
        </ul>
    </div>
    <input type="hidden" id="page" value="<?=$page?>" />
    <input type="hidden" id="surplus" value="<?=$total-count($list)?>" />
</div>
<div class="add"><i class="ico i-add"><a style="text-indent: -9999px;width: 100%;height: 100%" href="<?=U('/member/plan/add')?>">添加商业计划书</a></i></div>
<?php require_once APP_PATH .  '/views/plan/script.php'?>