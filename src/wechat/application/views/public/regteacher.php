<div class="wrap">
    <form class="ajax-form" action="<?=U('/public/regTeacher')?>" method="post" before="before_submit">
    <div class="box form">
        <ul>
            <li>
                <div class="lab cell">姓&nbsp; &nbsp; &nbsp; &nbsp;名：</div>
                <div class="cell">
                    <input id="name" name="name" type="text" class="inpt2" placeholder="">
                </div>
            </li>
            <li>
                <div class="lab cell">手机号码：</div>
                <div class="cell">
                    <input id="mobile" name="mobile" type="text" class="inpt2" placeholder="">
                </div>
            </li>
            <li>
                <div class="lab cell">擅&nbsp; &nbsp; &nbsp; &nbsp;长：</div>
                <div class="cell">
                    <input id="good_at" name="good_at" type="text" class="inpt2" placeholder="如：互联网类商业计划书">
                </div>
            </li>
            <li>
                <div class="lab cell">个人简介：</div>
                <div class="cell">
                    <textarea class="txtarea" id="description" name="description" placeholder="一段文字介绍，不超过500字。"></textarea>
                </div>
            </li>
        </ul>
    </div>

    <input type="submit" value="认   证" class="sub1">
    </form>
</div>
<block name="script">
<script>
    function before_submit(){
        if($.trim($('#name').val()) == ''){
            layer.alert('请填写姓名！');
            return false;
        }
        if($.trim($('#mobile').val()) == ''){
            layer.alert('请填写手机号码！');
            return false;
        }
        if($.trim($('#good_at').val()) == ''){
            layer.alert('请填写擅长！');
            return false;
        }
        if($.trim($('#description').val()) == ''){
            layer.alert('请填写个人简介！');
            return false;
        }
    }
</script>
</block>
