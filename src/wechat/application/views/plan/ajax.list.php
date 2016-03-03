<?php foreach($list as $item):?>
    <div class="box kejian">
        <div class="padm17">
            <div class="tit">
                <?php if(empty($item['wx_id'])):?>
                <a href="javascript:" class="right download down-file" item_id="<?=$item['id']?>">下载</a>
                <?php else:?>
                <a href="<?=get_qiniu_file_durl($item['file'])?>" class="right download">下载</a>
                <?php endif;?>
                <?=$item['title']?>
            </div>
            <div class="subtit">
                <div class="cell class"><?=$item['category_name']?></div>
                <div class="cell name"><?=$item['student_name']?></div>
                <?php $time = strtotime($item['insert_time']);?>
                <div class="cell date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></div>
            </div>
            <a href="<?=U('/plan/detail/id/'.$item['id'])?>">
                <div class="pic center">
                    <img src="<?=imageMogr2($item['logo'],325,175)?>" class="img">
                </div>
                <div class="desp">
                    <?=subtext($item['description'],40)?>
                </div>
            </a>
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
<?php endforeach;?>