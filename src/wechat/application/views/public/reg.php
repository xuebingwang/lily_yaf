<div class="wrap">
    <form class="ajax-form" action="<?=U('/public/reg')?>" method="post" before="before_submit">
    <div class="padtb17 orange">*：加入百合花，我们有很多名师，帮助您在商业再创辉煌...</div>
    <div class="box form">
        <ul>
            <li>
                <div class="lab cell">公司名称：</div>
                <div class="cell">
                    <input id="company" name="company" type="text" class="inpt2" placeholder="" value="<?=$user['company']?>">
                </div>
            </li>
            <li>
                <div class="lab cell">姓&nbsp; &nbsp; &nbsp; &nbsp;名：</div>
                <div class="cell">
                    <input id="name" name="name" type="text" class="inpt2" placeholder="" value="<?=$user['name']?>">
                </div>
            </li>
            <li>
                <div class="lab cell">手机号码：</div>
                <div class="cell">
                    <input id="mobile" name="mobile" type="text" class="inpt2" placeholder="" value="<?=$user['mobile']?>">
                </div>
            </li>
        </ul>
    </div>

    <input type="submit" value="报   道" class="sub1">
    <div class="padtb17 orange center">注：完成直接跳转到商业计划书展示</div>
    </form>
</div>
<block name="script">
<script>
    function before_submit(){
        if($.trim($('#company').val()) == ''){
            layer.alert('请填写公司名称！');
            return false;
        }
        if($.trim($('#name').val()) == ''){
            layer.alert('请填写姓名！');
            return false;
        }
        if($.trim($('#mobile').val()) == ''){
            layer.alert('请填写手机号码！');
            return false;
        }
    }
//    layer.open({content: '你点了确认', time: 100000});
//    layer.msg('aaa',function(){
//        console.info('aaa');
//    });
//    layer.load();
</script>

</block>
