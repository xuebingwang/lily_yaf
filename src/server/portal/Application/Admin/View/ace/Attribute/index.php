<extend name="Public/base"/>

<block name="body">
    <!-- 标题栏 -->
    <div class="page-header">
        <h1>
            属性列表(不含继承属性)
            <small>
                <i class="icon-double-angle-right"></i>
                {:get_model_by_id($model_id)}
            </small>
        </h1>
    </div>

	<!-- 数据列表 -->
    <div class="table-responsive">
        <div class="dataTables_wrapper">  

            <table class="table table-striped table-bordered table-hover dataTable">
			    <thead>
			        <tr>
					<th class="">编号</th>
					<th class="">字段</th>
					<th class="">名称</th>
					<th class="">数据类型</th>
					<th class="">操作</th>
					</tr>
			    </thead>
			    <tbody>
					<notempty name="_list">
					<volist name="_list" id="vo">
					<tr>
						<td>{$vo.id} </td>
						<td>{$vo.name}</td>
						<td><a data-id="{$vo.id}" href="{:U('Attribute/edit?id='.$vo['id'])}">{$vo.title}</a></td>
						<td><span>{:get_attribute_type($vo['type'])}</span></td>
						<td><a href="{:U('Attribute/edit?id='.$vo['id'])}">编辑</a>
							<a class="confirm ajax-get" href="{:U('Attribute/remove?id='.$vo['id'])}">删除</a>
			                </td>
					</tr>
					</volist>
					<else/>
					<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
					</notempty>
				</tbody>
		    </table>
            <div class="row">
                <div class="col-sm-4">
                    <label>
                        <a class="btn btn-white" href="{:U('Attribute/add?model_id='.$model_id)}">
                            新增
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <include file="Public/page"/>
                </div>
            </div>
        </div>
    </div>
</block>

<block name="script">
    <script src="__STATIC__/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">
  	//导航高亮
    highlight_subnav('{:U('Model/index')}');
    $(function(){
    	$("#search").click(function(){
    		var url = $(this).attr('url');
    		var status = $('select[name=status]').val();
    		var search = $('input[name=search]').val();
    		if(status != ''){
    			url += '/status/' + status;
    		}
    		if(search != ''){
    			url += '/search/' + search;
    		}
    		window.location.href = url;
    	});
})
</script>
</block>
