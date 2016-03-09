<div class="wrap">
    <form class="ajax-form" action="<?=U('/member/plan/add')?>" method="post" before="before_submit">
    <div class="box form">
        <ul>
            <li>
                <div class="lab cell">标题：</div>
                <div class="cell">
                    <input id="title" name="title" type="text" class="inpt2" placeholder="输入计划书标题">
                </div>
            </li>
            <li>
                <div class="lab cell">分类：</div>
                <div class="cell" id="cate_wrap">

                </div>
            </li>
            <li class="upload-wrap">
                <div class="lab cell">封面：</div>
                <div class="cell file-pic">
                    <img src="/images/p5.jpg" class="pic1">
                </div>
                <label class="upfile up1 cell">
                    <span class="btn1 pic-upload" name="logo" val="">上传</span>
                </label>
            </li>

            <li class="upload-wrap">
                <div class="lab cell">上传附件：</div>
                <div class="cell file-name show-name">

                </div>
                <label class="upfile up2 cell">
                    <span class="btn1 file-upload" name="plan_file" val="">上传</span>
                </label>
            </li>

            <li>
                <div class="lab3">计划书详细描述：</div>
                <div class="txt-right padt1">
                    <textarea class="txtarea" id="description" name="description"></textarea>
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
        if($.trim($('#title').val()) == ''){
            layer.alert('请填写计划书标题！');
            return false;
        }
        if($.trim($('#category').val()) == ''){
            layer.alert('请选择计划书分类！');
            return false;
        }
        if($.trim($('#logo').val()) == ''){
            layer.alert('请上传计划书封面！');
            return false;
        }
        if($.trim($('#plan_file').val()) == ''){
            layer.alert('请上传计划书附件！');
            return false;
        }
        if($.trim($('#description').val()) == ''){
            layer.alert('请填写计划书详细描述！');
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
                district: 'category',
                district_id: 'category',
                sel_wrap: '<span  class="dk_wrap w170"></span>'
            }
        );
    });
</script>

<script src="/js/linkagesel.js"></script>
<?php require_once __DIR__.'/../common/upload.js.php';?>
<?php require_once __DIR__.'/../common/upload.pic.php';?>
<?php require_once __DIR__.'/../common/upload.file.php';?>
</block>
