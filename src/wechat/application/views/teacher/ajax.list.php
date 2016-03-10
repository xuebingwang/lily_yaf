<?php foreach($list as $item):?>
    <div class="box kejian">
        <div class="padm17">
            <div class="tit">
                <a class="right weui_btn weui_btn_mini weui_btn_primary" href="<?=U('/plan/comment',['id'=>$item['id']])?>">
                    点赞
                </a>
                <?=$item['name']?>
            </div>
            <div class="subtit">
                <div class="cell class"><?=$item['good_at']?></div>
            </div>
            <a href="<?=U('/teacher/detail/id/'.$item['id'])?>">
                <div class="pic center">
                    <img src="<?= empty($item['pic_url']) ? "/images/p4.jpg" :  imageMogr2($item['pic_url'],325,175)?>" class="img">
                </div>
                <div class="desp">
                    <?=subtext($item['description'],40)?>
                </div>
            </a>
        </div>
    </div>
<?php endforeach;?>