<div class="wrap">
    <div class="box review">
        <div class="padm17">
            <div class="padtb17 orange">
                *：点击照片栏可将任意照片设为封面
            </div>
            <div class="padm">
                <form action="<?=U('/member/teacher/album')?>" method="post" class="ajax-form">
                    <div class="weui_cells weui_cells_radio" id="preview-img-wrap">

                    </div>
                    <label class="file upload-wrap" id="upload-wrap">
                        <img src="/images/pic1.jpg"  class="pic2" id="pic-upload">
                        添加图片
                    </label>
                    <input type="submit" class="sub2" value="保   存">
                </form>
            </div>
            <div class="padtb17 orange">
                *：最多允许上传三张照片，建议图片尺寸为640x308像素
            </div>
        </div>

    </div>
</div>
<block name="script">
    <?php require_once APP_PATH.'modules/Member/views/common/upload.js.php';?>
    <script>
        $(function(){

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
                                    add_pic(resp.url,Qiniu.getUrl(info.key));
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
                $(this).closest('.weui_check_label').remove();
                var len = $("#preview-img-wrap .weui_check_label").length;
                if(len < 3){
                    $("#upload-wrap").show();
                }
            })

            var _index = 0;
            function add_pic(url,source,is_cover){
                var len = $("#preview-img-wrap .weui_check_label").length;
                is_cover = is_cover || len == 0;
                $("#preview-img-wrap").append(
                    '<label for="check_box_'+_index+'" class="weui_cell weui_check_label">' +
                        '<div class="weui_cell_bd weui_cell_primary">' +
                            '<img class="weui_media_appmsg_thumb" src="'+url+'" alt="">' +
                            '<input type="hidden" name="pic_url[]" value="'+source+'">' +
                        '</div>' +
                        '<div class="weui_cell_ft">' +
                            '<input value="'+source+'" type="radio" '+(is_cover ? 'checked="checked"' : '')+' id="check_box_'+_index+'" class="weui_check" name="is_cover">' +
                            '<span class="weui_icon_checked"></span>' +
                        '</div>' +
                        '<a href="javascript:;" class="weui_progress_opr">' +
                            '<i class="weui_icon_cancel"></i>' +
                        '</a>' +
                    '</label>');
                if(len+1 > 2){
                    $("#upload-wrap").hide();
                }
                _index++;
            }

            <?php if(!empty($list)):?>
            $.each(<?=json_encode($list)?>,function(k,v){
                add_pic(v.url, v.source, v.is_cover);
            });
            <?php endif;?>
        });

    </script>
</block>