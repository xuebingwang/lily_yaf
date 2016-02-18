<h1><?=$message?></h1>
<?php if(!empty($jumpUrl)):?>
    <block name="script">
        <script>
            setTimeout(function(){
                window.location = '<?=$jumpUrl?>';
            },<?=$waitSecond*1000?>);
        </script>
    </block>
<?php endif;?>