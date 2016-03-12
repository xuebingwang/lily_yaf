<extend name="Public/base" />

<block name="body">
	<div class="form-horizontal">
		<!-- 基础 -->
		
    <?php 
        echo ace_group(ace_label('openid'),'<div class="line-height-2 blue">'.$wx_info['openid'].'</div>');
        echo ace_group(ace_label('微信昵称'),'<div class="line-height-2 blue">'.$wx_info['nickname'].'</div>');
        echo ace_group(ace_label('性別'),'<div class="line-height-2 blue">'.get_sex($wx_info['sex']).'</div>');
        echo ace_group(ace_label('语言'),'<div class="line-height-2 blue">'.$wx_info['language'].'</div>');
        echo ace_group(ace_label('城市'),'<div class="line-height-2 blue">'.$wx_info['city'].'</div>');
        echo ace_group(ace_label('省份'),'<div class="line-height-2 blue">'.$wx_info['province'].'</div>');
        echo ace_group(ace_label('国家'),'<div class="line-height-2 blue">'.$wx_info['country'].'</div>');
        echo ace_group(ace_label('头像'),'<div class="line-height-2 blue">
            <img width="150" height="150" src="'.$wx_info['headimgurl'].'"/></div>');
        echo ace_group(ace_label('unionid'),'<div class="line-height-2 blue">'.$wx_info['unionid'].'</div>');
        echo ace_textarea(array('label_text'=>'备注'),'',$wx_info['remark']);
        echo ace_group(ace_label('用户分组'),'<div class="line-height-2 blue">'.$wx_info['groupid'].'</div>');
        echo ace_group(ace_label('关注标示'),'<div class="line-height-2 blue">'.get_subscribe($wx_info['subscribe']).'</div>');
        echo ace_group(ace_label('关注时间'),'<div class="line-height-2 blue">'.date('Y-m-d H:i:s',$wx_info['subscribe_time']).'</div>');
        echo ace_group(ace_label('审核状态'),'<div class="line-height-2 blue apply_status" data-id="' .$info['id'] .'">'.
            gen_select(['WAT'=>'待审核','YES'=>'审核通过','NO#'=>'审核拒绝'],$info['apply_status']).
            '</div>');
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
    //发送id及选中value到后台
    $('.apply_status').each(function (idx, itm) {
        var elm = $($(itm).children()[0]);
        var old_value = elm.val();
        elm.change(function (e) {
            var option = $(this),
                $this = option.parent();
            $.ajax({
                method: "POST",
                dataType: "json",
                url: "<?= U('Teacher/status') ?>",
                data: {id: $this.data('id'), status: option.val()},
                success: function (data) {
                    if (data.status === 1) {
                        layer.msg(data.msg);
                        option.val(data.value)
                    } else {
                        layer.msg(data.msg);
                        option.val(old_value);
                    }
                }
            });
        });
    });
</script>
</block>

