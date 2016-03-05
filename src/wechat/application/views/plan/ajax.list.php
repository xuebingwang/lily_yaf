<?php foreach($list as $item):?>
    <div class="box kejian">
        <div class="padm17">
            <div class="tit">
            <?php
            $vars = [];
            if(isset($is_teacher) && $is_teacher):
                $vars['is_teacher'] = 1;
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
            <?php endif;?>
                <?=$item['title']?>
            </div>
            <div class="subtit">
                <div class="cell class"><?=$item['category_name']?></div>
                <div class="cell name"><?=$item['student_name']?></div>
                <?php $time = strtotime($item['insert_time']);?>
                <div class="cell date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></div>
            </div>
            <a href="<?=U('/plan/detail/id/'.$item['id'],$vars)?>">
                <div class="pic center">
                    <img src="<?=imageMogr2($item['logo'],325,175)?>" class="img">
                </div>
                <div class="desp">
                    <?=subtext($item['description'],40)?>
                </div>
            </a>
            <div class="bar">
                <span>
                <?php if(empty($item['wx_id'])):?>
                    <a href="javascript:" class="down-file" item_id="<?=$item['id']?>"><i class="ico i-down"></i></a>
                <?php else:?>
                    <a href="<?=get_qiniu_file_durl($item['file'])?>"><i class="ico i-down"></i></a>
                <?php endif;?>
                    <span class="num green"><?=$item['down_count']?></span>
                </span>
                <span class="padl6">
                    <a href="<?=U('/plan/detail/id/'.$item['id'],$vars)?>">
                        <i class="ico i-eye"></i>
                    </a>
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
<?php endforeach;?>