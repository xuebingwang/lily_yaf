<extend name="Public/base" />

<block name="body">
	<!-- 表单 -->
	<form id="form" action="{:U('add')}" method="post" class="form-horizontal">
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>菜单名称</label>
			<div class="col-xs-12 col-sm-5">
				<input type="text" class="width-50" name="name" value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>跳转URL</label>
			<div class="col-xs-12 col-sm-5">
				<input type="text" class="width-100" name="url" value="">
			</div>
		</div>
        <div class="clearfix form-actions">
            <input type="hidden" name="pid" value="{$parent.id}">
            <div class="col-xs-6 center">
                <button type="submit" target-form="form-horizontal" class="btn btn-success ajax-post no-refresh" id="sub-btn">
                    <i class="icon-ok bigger-110"></i> 确认保存
                </button> 
                <button type="reset" class="btn" id="reset-btn">
                    <i class="icon-undo bigger-110"></i> 重置
                </button>   
                <a onclick="history.go(-1)" class="btn btn-info" href="javascript:;">
	               <i class="icon-reply"></i>返回上一页
	            </a>  
            </div>
        </div>
	</form>
</block>
