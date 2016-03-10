<extend name="Public/base" />

<block name="body">
	<div class="form-horizontal">
		<!-- 基础 -->
		
    <?php 
        echo ace_group(ace_label('openid'),'<div class="line-height-2 blue">'.$info['openid'].'</div>');
        echo ace_group(ace_label('微信昵称'),'<div class="line-height-2 blue">'.$info['nickname'].'</div>');
        echo ace_group(ace_label('性別'),'<div class="line-height-2 blue">'.get_sex($info['sex']).'</div>');
        echo ace_group(ace_label('语言'),'<div class="line-height-2 blue">'.$info['language'].'</div>');
        echo ace_group(ace_label('城市'),'<div class="line-height-2 blue">'.$info['city'].'</div>');
        echo ace_group(ace_label('省份'),'<div class="line-height-2 blue">'.$info['province'].'</div>');
        echo ace_group(ace_label('国家'),'<div class="line-height-2 blue">'.$info['country'].'</div>');
        echo ace_group(ace_label('图像地址'),'<div class="line-height-2 blue">'.$info['headimgurl'].'</div>');
        echo ace_group(ace_label('unionid'),'<div class="line-height-2 blue">'.$info['unionid'].'</div>');
        echo ace_textarea(array('label_text'=>'备注'),'',$info['remark']);
        echo ace_group(ace_label('用户分组'),'<div class="line-height-2 blue">'.$info['groupid'].'</div>');
        echo ace_group(ace_label('关注标示'),'<div class="line-height-2 blue">'.get_subscribe($info['subscribe']).'</div>');
        echo ace_group(ace_label('关注时间'),'<div class="line-height-2 blue">'.date('Y-m-d H:i:s',$info['subscribe_time']).'</div>');
    ?>
		<!-- 按钮 -->
		<div class="clearfix form-actions">
             <div class="col-xs-12">
                 <a onclick="history.go(-1)" class="btn btn-white" href="javascript:;">
	                返回上一页
	             </a>  
             </div>
         </div>
	</div>
</block>
<block name="script">
<script type="text/javascript" charset="utf-8">

</script>
</block>

