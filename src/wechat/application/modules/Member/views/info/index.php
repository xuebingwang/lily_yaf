<div class="person">
    <label class="upload upload-wrap">
        <div id="upload-head" class="pic_tb" name="head_logo" val="<?=$user['head_logo']?>">
            <img src="<?=empty($user['head_logo']) ? $user['headimgurl'] : imageView2($user['head_logo'])?>" class="img0 avtar" >
        </div>
    </label>
    <div class="name"><?=$user['name']?></div>
    <div class="com"><?=$user['company']?></div>
    <p class="tips">注：直接点击头像换头像</p>
</div>

<div class="wrap">
    <div class="box list1">
        <ul>
            <li>
                <a href="<?=U('/member/info/edit')?>">
                    <div class="cell"><i class="ico i-info"></i> 我的资料</div>
                    <div class="cell arr-right"><i class=" ico i-right"></i></div>
                </a>
            </li>

            <li>
                <a href="<?=U('/member/plan/index')?>">
                    <div class="cell"><i class="ico i-files"></i> 我的课件</div>
                    <div class="cell arr-right"><i class=" ico i-right"></i></div>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php require_once APP_PATH.'views/common/nav.php'?>
<block name="script">
<?php require_once __DIR__.'/../common/upload.js.php';?>
<script>
$(function(){
    <?php
        $config = Yaf\Registry::get('config');
    ?>
    $.getJSON('<?=U('/member/upload/picToken')?>',function(resp){
        Qiniu.token = resp.uptoken;

        var target = $('#upload-head');
        var id = target.attr('id');

        target.after('<input id="'+target.attr('name')+'" name="'+target.attr('name')+'" value="'+target.attr('val')+'" type="hidden" />');

        var parent = target.parent();

        var uploader = Qiniu.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: id,
            max_file_size : '<?=$config->upload->pic->maxSize?>', //最大上传
            filters: {
                mime_types : [ //允许上传文件后辍
                    { title : "Image files", extensions : "<?=$config->upload->pic->exts?>" }
                ]
            },
            flash_swf_url: '/js/plupload/Moxie.swf',
            dragdrop: true,
            chunk_size: '4mb',
            uptoken_url: '<?=U('/member/upload/picToken')?>',
            domain: 'http://<?=$config->qiniu->picture->domain?>/',
            get_new_uptoken: false,
            downtoken_url: '<?=U('/member/upload/editheadlogo')?>',
            unique_names: true,
            multi_selection:false,
            auto_start: true,
            init: {
                'FilesAdded': function(up, files) {
                },
                'BeforeUpload': function(up, file) {
                    parent.after(
                        '<div class="weui_progress" style="width:80%; margin:0 auto;">' +
                            '<div class="weui_progress_bar">' +
                                '<div style="width: 0%;" class="weui_progress_inner_bar js_progress"></div>' +
                            '</div>' +
                            '<a class="weui_progress_opr" href="javascript:;">' +
                                '<i class="weui_icon_cancel"></i>' +
                            '</a>' +
                        '</div>');

                    parent.next().find('.weui_icon_cancel').click(function(){
                        parent.next().remove();
                        up.removeFile(file);
                    })
                },
                'UploadProgress': function(up, file) {
                    parent.next().find('.js_progress')
                        .css('width',file.percent + '%');
                },
                'UploadComplete': function() {
                    parent.next().remove();
                },
                'FileUploaded': function(up, file, info) {
                    $.post(up.settings.downtoken_url,{
                        source:Qiniu.getUrl(info.key)
                    },function(resp){
                        if(resp.status == '0'){
                            target.closest('.upload-wrap')
                                .find('img').attr('src',resp.url);
                        }else{
                            layer.alert(resp.msg||'头像保存失败，请重新再试或联系客服人员！');
                        }
                    },'json');
                },
                'Error': function(up, err, errTip) {
                    layer.alert(errTip);
                    target.show().prev().remove();
                }
            }
        });
    });
})
</script>
</block>