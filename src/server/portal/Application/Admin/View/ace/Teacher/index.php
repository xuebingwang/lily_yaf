<extend name="Public/base" />

<block name="body">
    <div class="table-responsive">
        <div class="dataTables_wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="search-form">
                        <label>用户昵称或者ID
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
                    <th class="">用户名</th>
                    <th class="">擅长</th>
                    <th class="">审核状态</th>
                    <th class="">用户状态</th>
                    <th class="">操作</th>
                </tr>
                </thead>
                <tbody>
                <notempty name="_list">
                    <volist name="_list" id="vo">
                        <tr>
                            <td>{$vo.name}</a></td>
                            <td>{$vo.good_at}</td>
                            <td class="apply_status" data-id="{$vo.id}">
                                {$vo.apply_status|gen_select=['WAT'=>'待审核','YES'=>'审核通过','NO#'=>'审核拒绝'],###}
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" class="ace ace-switch ace-switch-6 ajax-get no-refresh" name="subscribe" value="{$vo.subscribe}" <?=$vo['status'] == 'OK#' ? 'checked' : ''?>>
                                    <span class="lbl"></span>
                                </label>
                            </td>
                            <td>
                                <a href="{:U('Teacher/edit?id='.$vo['id'])}">详情</a>
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
                    data: { id: $this.data('id'), status: option.val()  },
                    success: function (data) {
                        if (data.status === 1) {
                            layer.msg(data.msg);
                            option.val(data.value)
                        } else  {
                            layer.msg(data.msg);
                            option.val(old_value);
                        }
                    }
                });
            });
        });
    </script>
</block>
