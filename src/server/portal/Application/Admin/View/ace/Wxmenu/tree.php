<volist name="tree" id="list">
	<dl class="cate-item">
		<dt class="cf">
			<form action="{:U('edit')}" method="post">
				<div class="btn-toolbar opt-btn cf">
					<a title="编辑" href="{:U('edit?id='.$list['id'].'&pid='.$list['pid'])}">编辑</a>
					<a title="删除" href="{:U('remove?id='.$list['id'].'&pid='.$list['pid'])}" class="confirm ajax-get">删除</a>
				</div>
				<div class="fold"><i></i></div>
				<div class="name">
					<span class="tab-sign"></span>
					<input type="hidden" name="id" value="{$list.id}">
					<input type="text" name="title" class="text" value="{$list.name}">
                                        <?php if(!$list['pid'] && count($list['_']) < 5): ?>
					<a class="add-sub-cate" title="添加子分类" href="{:U('add?pid='.$list['id'])}">
						<i class="icon-add"></i>
					</a>
                                        <?php endif; ?>
					<span class="help-inline msg"></span>
				</div>
			    <div style="clear: both;"></div>
			</form>
		</dt>
		<notempty name="list['_']">
			<dd>
				{:R('Wxmenu/tree', array($list['_']))}
			</dd>
		</notempty>
	</dl>
</volist>