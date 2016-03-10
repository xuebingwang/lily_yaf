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
            </div>
            <a href="<?=U('/plan/detail/id/'.$item['id'])?>">
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