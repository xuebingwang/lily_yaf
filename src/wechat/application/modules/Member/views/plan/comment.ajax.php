<?php foreach($list as $item): ?>
    <li>
        <a href="<?=U('/plan/comment' , ['id' => $item['id']] )?>">
            <div class="cell">
                <img src="<?=imageView2($item['logo'],51,46)?>" class="pic">
                <?=$item['title']?>
            </div>
            <div class="cell arr-right">
                <?php if($item['comment_count'] > 0): ?>
                    <span class="num"><?=$item['comment_count']?></span>
                <?php endif; ?>
                <i class="ico i-right"></i>
            </div>
        </a>
    </li>
<?php endforeach; ?>