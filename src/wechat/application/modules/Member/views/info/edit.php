<div class="wrap">
    <form class="ajax-form" action="<?=U('/member/info/edit')?>" method="post" before="before_submit">
    <div class="box form">
        <ul>
            <li>
                <div class="lab cell">公司名称：</div>
                <div class="cell">
                    <input id="company" name="company" type="text" class="inpt2" value="<?=$item['company']?>">
                </div>
            </li>
            <li>
                <div class="lab cell">公司行业：</div>
                <div class="cell" id="cate_wrap">

                </div>
            </li>
            <li>
                <div class="lab cell">公司规模：</div>
                <div class="cell">
                    <span  class="dk_wrap w170">
                        <select name="company_scale" id="company_scale">
                            <option value="0">小于50人</option>
                            <option value="1">50-99人</option>
                            <option value="2">100-499人</option>
                            <option value="3">500-999人</option>
                            <option value="4">大于1000人</option>
                        </select>
                    </span>
                </div>
            </li>
            <li>
                <div class="lab cell">手机号码：</div>
                <div class="cell">
                    <input id="mobile" name="mobile" type="text" class="inpt2" value="<?=$item['mobile']?>">
                </div>
            </li>
            <li>
                <div class="lab cell">姓&nbsp; &nbsp; &nbsp; &nbsp;名：</div>
                <div class="cell">
                    <input id="name" name="name" type="text" class="inpt2" value="<?=$item['name']?>">
                </div>
            </li>
        </ul>
    </div>

    <input type="submit" value="提   交" class="sub1">
    </form>
</div>
<block name="script">
<script>
    function before_submit(){
        if($.trim($('#company').val()) == ''){
            layer.alert('请填写公司名称！');
            return false;
        }
        if($.trim($('#mobile').val()) == ''){
            layer.alert('请填写手机号码！');
            return false;
        }
        if($.trim($('#name').val()) == ''){
            layer.alert('请填写姓名！');
            return false;
        }
    }

    $(function(){

        XBW.linkage.data = <?=json_encode($cate_list)?>;
        XBW.linkage.init(
            {
                selector: "#cate_wrap",
                root    : 0,
                emptyText: false,
                district: 'company_industry',
                district_id: 'company_industry',
                selected:'<?=$item['company_industry']?>',
                sel_wrap: '<span class="dk_wrap w170"></span>'
            }
        );
        $('#company_scale').val('<?=intval($item['company_scale'])?>');
    });
</script>

<script src="/js/linkagesel.js"></script>

</block>
