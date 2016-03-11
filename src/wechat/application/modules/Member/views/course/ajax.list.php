<?php foreach($list as $item):?>
    <div class="box kejian">
        <div class="padm17">
            <div class="tit">
                <?=$item['title']?>
            </div>
            <div class="subtit">
                <div class="cell class"><?=$item['category_name']?></div>
                <div class="cell name"><?=$item['name']?></div>
                <?php $time = strtotime($item['insert_time']);?>
                <div class="cell date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></div>
            </div>
            <a href="<?= U('/teacher/detailCourse/id/' . $item['id']) ?>">
                <div class="pic center">
                    <img src="<?=imageMogr2($item['logo'],325,175)?>" class="img">
                </div>
                <div class="desp">
                    <?=subtext($item['description'],40)?>
                </div>
            </a>
        </div>
    </div>
<?php endforeach;?>