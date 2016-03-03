<script>
    /*global Qiniu */
    /*global plupload */
    /*global FileProgress */
    /*global hljs */
    <?php
    $config = Yaf\Registry::get('config');
    ?>

    var Qiniu2 = new QiniuJsSDK();

    $(function() {
        $.getJSON('<?=U('/member/upload/fileToken')?>',function(resp){
            Qiniu2.token = resp.uptoken;
        });
        $.fn.file_upload = function() {
            this.each(function(){
                var target, id;

                target = $(this);
                id = target.attr('id');

                target.after('<input id="'+target.attr('name')+'" name="'+target.attr('name')+'" value="'+target.attr('val')+'" type="hidden" />');

                if (!id) {
                    id = plupload.guid();
                    target.attr('id', id);
                }

                var uploader = Qiniu2.uploader({
                    runtimes: 'html5,flash,html4',
                    browse_button: id,
                    max_file_size : '<?=$config->upload->file->maxSize?>', //最大上传
                    filters: {
                        mime_types : [ //允许上传文件后辍
                            { title : "Choose files", extensions : "<?=$config->upload->file->exts?>" }
                        ]
//                        ,
//                        prevent_duplicates : true //不允许选取重复文件
                    },
                    flash_swf_url: '/js/plupload/Moxie.swf',
                    dragdrop: true,
                    chunk_size: '4mb',
                    uptoken_url: '<?=U('/member/upload/picToken')?>',
                    domain: 'http://<?=$config->qiniu->file->domain?>/',
                    get_new_uptoken: false,
                    downtoken_url: '<?=U('/member/upload/downFileToken')?>',
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

                            target.closest('.upload-wrap')
                                .find('.show-name').text(file.name);

                            target.prev().find('.weui_icon_cancel').click(function(){

                                target.show().prev().remove();
                                target.closest('.upload-wrap')
                                    .find('.show-name').text('已取消');
                                up.removeFile(file);
                            })
                        },
                        'UploadProgress': function(up, file) {
                            target.next().find('.js_progress')
                                .css('width',file.percent + '%');
                        },
                        'UploadComplete': function() {
                            target.show().prev().remove();
                        },
                        'FileUploaded': function(up, file, info) {
                            target.next('input').val(Qiniu2.getUrl(info.key));
                        },
                        'Error': function(up, err, errTip) {
                            layer.alert(errTip);
                            target.show().prev().remove();
                        }
                    }
                });

            });
        }

        $('.file-upload').file_upload();
    });
</script>