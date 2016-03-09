<div class="wrap">
    <div class="box review">
        <div class="padm17">
            <div class="tit"><?=$item['title']?></div>
            <?php
            if(!empty($list)):
                $first = array_shift($list);
            ?>
            <div class="table review-info bordb">
                <div class="cell avtar1"><img src="<?=imageView2($first['headimgurl'],40,40)?>" class="img"></div>
                <div class="cell">
                    <?php $time = strtotime($first['insert_time']);?>
                    <div class="name"><?=$first['name']?><span class="date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></span></div>
                    <p class="txt"><?=$first['content']?></p>
                    <?php foreach($first['att'] as $att):?>
                    <div class="file">
                        <a href="<?=get_qiniu_file_durl($att['att_url'])?>" class="down2">
                            <i class="ico i-file"></i> <?=subtext($att['att_name'],25)?>
                            <i class="ico i-down2"></i>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
            <?php foreach($list as $one):?>
            <div class="table review-info">
                <div class="cell avtar2"><img src="<?=imageView2($one['headimgurl'],22,22)?>" class="img"></div>
                <div class="cell">

                    <?php $time = strtotime($one['insert_time']);?>
                    <div class="name"><?=$one['name']?><span class="date"><?=date('Y-m-d',$time)?> <span class="padl20"><?=date('H:i',$time)?></span></span></div>
                    <p class="txt"><?=$one['content']?></p>
                    <?php foreach($one['att'] as $att):?>
                    <div class="file">
                        <a href="<?=get_qiniu_file_durl($att['att_url'])?>" class="down2">
                            <i class="ico i-file"></i> <?=subtext($att['att_name'],15)?>
                            <i class="ico i-down2"></i>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
            <div class="padm">
                <form action="<?=U('/plan/comment')?>" method="post" class="ajax-form">
                    <input name="id" value="<?=$item['id']?>" type="hidden">

                    <textarea  class="txtarea2" placeholder="发表自己的观点和评论" name="content"></textarea>
                    <div class="weui_panel weui_panel_access">
                        <div class="weui_panel_bd" id="preview-img-wrap">
                        </div>
                    </div>
                    <label class="file upload-wrap">
                        <img src="/images/pic1.jpg"  class="pic2" id="pic-upload">
                        添加图片
                    </label>
                    <input type="submit" class="sub2" value="发   送">
                </form>
            </div>
        </div>

    </div>
</div>
<block name="script">
<?php require_once APP_PATH.'modules/Member/views/common/upload.js.php';?>
<script>
$(function(){

    var _att_index = 0;
    <?php
        $config = Yaf\Registry::get('config');
    ?>
    $.getJSON('<?=U('/member/upload/picToken')?>',function(resp){
        Qiniu.token = resp.uptoken;

        var target = $('#pic-upload');
        var parent = target.parent();
        Qiniu.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: target.attr('id'),
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
            downtoken_url: '<?=U('/member/upload/downToken')?>',
            unique_names: true,
            multi_selection:false,
            auto_start: true,
            init: {
                'FilesAdded': function(up, files) {

                },
                'BeforeUpload': function(up, file) {
                    parent.after(
                        '<div class="weui_progress" style="margin:.5rem auto;">' +
                            '<div class="weui_progress_bar">' +
                                '<div style="width: 0%;" class="weui_progress_inner_bar js_progress"></div>' +
                            '</div>' +
                            '<a class="weui_progress_opr" href="javascript:;">' +
                                '<i class="weui_icon_cancel"></i>' +
                            '</a>' +
                        '</div>');

                    parent.next().find('.weui_icon_cancel').click(function(){
                        target.next().remove();
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
                        url:Qiniu.imageView2({mode:1,w:30,h:30},info.key)
                    },function(resp){
                        if(resp.url){

                            $("#preview-img-wrap").append(
                                '<div class="weui_media_box weui_media_appmsg">' +
                                    '<div class="weui_media_hd">' +
                                        '<img class="weui_media_appmsg_thumb" src="'+resp.url+'" alt="">' +
                                        '<input type="hidden" name="att['+_att_index+'][att_url]" value="'+Qiniu.getUrl(info.key)+'">' +
                                        '<input type="hidden" name="att['+_att_index+'][att_name]" value="'+file.name+'">' +
                                        '<input type="hidden" name="att['+_att_index+'][att_suffix]" value="'+file.type+'">' +
                                    '</div>' +
                                    '<div class="weui_media_bd">' +
                                        '<p class="weui_media_desc">'+file.name+'</p>' +
                                    '</div>' +
                                    '<a href="javascript:;" class="weui_progress_opr">' +
                                        '<i class="weui_icon_cancel"></i>' +
                                    '</a>' +
                                '</div>');
                            _att_index++;
                        }else{
                            layer.alert(resp.msg||'图片上传失败，请重新再试或联系客服人员！');
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

    $(document).on('click','#preview-img-wrap .weui_progress_opr',function(){
        $(this).closest('.weui_media_box').remove();
    })
})
</script>
</block>