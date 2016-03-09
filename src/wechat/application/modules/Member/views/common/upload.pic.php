<script>
    /*global Qiniu */
    /*global plupload */
    /*global FileProgress */
    /*global hljs */
    <?php
    $config = Yaf\Registry::get('config');
    ?>
    $(function() {

        $.getJSON('<?=U('/member/upload/picToken')?>',function(resp){
            Qiniu.token = resp.uptoken;
        });

        $.fn.pic_upload = function() {
            this.each(function(){
                var target, id;

                target = $(this);
                id = target.attr('id');

                target.after('<input id="'+target.attr('name')+'" name="'+target.attr('name')+'" value="'+target.attr('val')+'" type="hidden" />');

                if (!id) {
                    id = plupload.guid();
                    target.attr('id', id);
                }

                var uploader = Qiniu.uploader({
                    runtimes: 'html5,flash,html4',
                    browse_button: id,
                    max_file_size : '<?=$config->upload->pic->maxSize?>', //最大上传
                    filters: {
                        mime_types : [ //允许上传文件后辍
                            { title : "Image files", extensions : "<?=$config->upload->pic->exts?>" }
                        ]
//                        ,
//                        prevent_duplicates : true //不允许选取重复文件
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
                            target.hide().before(
                            '<div class="weui_progress">' +
                                '<div class="weui_progress_bar">' +
                                    '<div style="width: 0%;" class="weui_progress_inner_bar js_progress"></div>' +
                                '</div>' +
                                '<a class="weui_progress_opr" href="javascript:;">' +
                                    '<i class="weui_icon_cancel"></i>' +
                                '</a>' +
                            '</div>');

                            target.prev().find('.weui_icon_cancel').click(function(){
                                target.show().prev().remove();
                                up.removeFile(file);
                            })
                        },
                        'UploadProgress': function(up, file) {
                            target.prev().find('.js_progress')
                                .css('width',file.percent + '%');
                        },
                        'UploadComplete': function() {
                            target.show().prev().remove();
                        },
                        'FileUploaded': function(up, file, info) {
                            target.next().val(Qiniu.getUrl(info.key));
                            $.post(up.settings.downtoken_url,{
                                source:Qiniu.imageView2({mode:1,w:106,h:71},info.key)
                            },function(resp){
                                target.closest('.upload-wrap')
                                    .find('img').attr('src',resp.source);
                            },'json');
                        },
                        'Error': function(up, err, errTip) {
                            layer.alert(errTip);
                            target.show().prev().remove();
                        }
                    }
                });

            });
        }

        $('.pic-upload').pic_upload();
    });
</script>