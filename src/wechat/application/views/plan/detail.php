<div class="wrap">
    <div class="box kejian">
        <div class="padm17">
            <div class="tit">
            <?php
            if(intval(I('is_teacher')) > 0):
            ?>
                <a class="right weui_btn weui_btn_mini weui_btn_primary" href="<?=U('/plan/comment/id/'.$item['id'])?>">
                    点评
                </a>
            <?php else:?>
                <?php if(empty($item['wx_id'])):?>
                <a href="javascript:" class="right download down-file" item_id="<?=$item['id']?>">下载</a>
                <?php else:?>
                <a href="<?=get_qiniu_file_durl($item['file'])?>" class="right download">下载</a>
                <?php endif;?>
                <?=$item['title']?>
            <?php endif;?>
            </div>
            <div class="subtit">
                <div class="cell class"><?=$item['student_name']?></div>
                <div class="cell name"><?=$item['company']?></div>
                <?php $time = strtotime($item['insert_time']);?>
                <div class="cell date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></div>
            </div>
            <div class="desp mih7">
                <?=$item['description']?>
            </div>
            <div class="bar">
                <span>
                    <i class="ico i-down"></i>
                    <span class="num green"><?=$item['down_count']?></span>
                </span>
                <span class="padl6">
                    <i class="ico i-eye"></i>
                    <span class="num corl2"><?=$item['view_count']?></span>
                </span>
                <span class="padl6">
                    <a class="like-plan" href="javascript:" url="<?=U('/plan/like/',['id'=>$item['id']])?>">
                        <i class="ico i-zan"></i>
                    </a>
                    <span class="num orange"><?=$item['like_count']?></span>
                </span>
            </div>
        </div>
    </div>
</div>
<?php require_once 'script.php'?>