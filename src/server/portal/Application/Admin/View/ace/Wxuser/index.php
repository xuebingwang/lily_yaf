<extend name="Public/base" />

<block name="body">
     <div class="table-responsive">
        <div class="dataTables_wrapper">  
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="search-form">
                        <label>微信用户昵称或者ID
                            <input type="text" class="search-input" name="nickname" value="{:I('nickname')}" placeholder="请输入用户昵称或者ID">
                        </label>
                        <label>
                            <button class="btn btn-sm btn-primary" type="button" id="search" url="{:U('index')}">
                               <i class="icon-search"></i>搜索
                            </button>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- 数据列表 -->
            <table class="table table-striped table-bordered table-hover dataTable">
			    <thead>
			        <tr>
                        <th class="">openid</th>
                        <th class="">微信昵称</th>
                        <th class="">性别</th>
                        <th class="">关注标示</th>
                        <th class="">状态</th>
                        <th class="">操作</th>
					</tr>
			    </thead>
			    <tbody>
					<notempty name="_list">
					<volist name="_list" id="vo">
					<tr>
						<td>{$vo.openid}</a></td>
						<td>{$vo.nickname}</td>
						<td>{$vo.sex|get_sex}</td>
						<td>{$vo.subscribe|get_subscribe}</td>
						<td>
                            <?php
                                $url = U('User/changeStatus?method=resumeUser&id='.$vo['id']);
                                if($vo['subscribe'] == '1'){
                                    $url = U('User/changeStatus?method=forbidUser&id='.$vo['id']);
                                }
                            ?>
                            <label>
                                <input type="checkbox" class="ace ace-switch ace-switch-6 ajax-get no-refresh" name="subscribe" value="{$vo.subscribe}" <?=$vo['subscribe'] == '1' ? 'checked' : ''?> url="<?=$url?>">
                                <span class="lbl"></span>
                            </label>
                        </td>
                        <td>
                            <a href="{:U('Wxuser/edit?id='.$vo['wx_id'])}">详情</a>
                        </td>
					</tr>
					</volist>
					<else/>
					<td colspan="9" class="text-center"> aOh! 暂时还没有内容! </td>
					</notempty>
				</tbody>
            </table>
        </div>
    </div>
</block>

<block name="script">
	<script src="__STATIC__/thinkbox/jquery.thinkbox.js"></script>

	<script type="text/javascript">
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
		    console.info($("#search"));
			$("#search").click();
			return false;
		}
	});

	</script>
</block>
