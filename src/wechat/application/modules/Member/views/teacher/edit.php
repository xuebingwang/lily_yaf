<div class="wrap">
    <form class="ajax-form" action="<?=U('/member/teacher/edit')?>" method="post" before="before_submit">
        <div class="padtb17 orange">
            *：欢迎加入百合花，在这里，您可以认识更多的学生，有更多的市场机会。
        </div>
        <div class="box form">
            <ul>
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
                <li>
                    <div class="lab cell">擅&nbsp; &nbsp; &nbsp; &nbsp;长：</div>
                    <div class="cell">
                        <input id="good_at" name="good_at" type="text" class="inpt2" placeholder="如：互联网类商业计划书" value="<?=$item['good_at']?>">
                    </div>
                </li>

                <li class="upload-wrap">
                    <div class="lab cell">二维码：</div>
                    <div class="cell file-name show-name">
                        <img src="<?= empty($item['qr_code_url']) ? "/images/p5.jpg" : imageView2($item['qr_code_url']) ?>" class="pic1">
                    </div>
                    <label class="upfile up2 cell">
                        <span class="btn1 pic-upload" name="qr_code_url" val="<?= $item['qr_code_url'] ?>">上传</span>
                    </label>
                </li>


                <li>
                    <div class="lab cell">个人简介：</div>
                    <div class="cell">
                        <textarea class="txtarea" id="description" name="description" placeholder="一段文字介绍，不超过500字。"> <?=$item['description']?></textarea>
                    </div>
                </li>
            </ul>
        </div>

        <input type="submit" value="修   改" class="sub1">
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
            if($.trim($('#qr_code_url').val()) == ''){
                layer.alert('请上传二维码！');
                return false;
            }

            if($.trim($('#description').val()) == ''){
                layer.alert('请填写个人简介！');
                return false;
            }
        }
    </script>

    <?php require_once __DIR__.'/../common/upload.js.php';?>
    <?php require_once __DIR__.'/../common/upload.pic.php';?>
</block>
