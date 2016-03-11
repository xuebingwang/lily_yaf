<block name="script">
<script>
    $(function(){
        $(document).on('click','.down-course',function(){
            var $this = $(this);
            $.getJSON('<?=U('/teacher/downCourse')?>',{id:$this.attr('item_id')},function(resp){
                if(resp.status == '0'){
                    $this.attr('href',resp.url).removeClass('down-file');
                    window.location = resp.url;
                }else{
                    layer.alert(resp.msg);
                }
            });
        });
        $(document).on('click','.like-plan',function(){
            var $this = $(this);
            $.getJSON($this.attr('url'),function(resp){
                if(resp.status == '0'){
                    $this.removeClass('like-plan')
                        .next().text(resp.count);
                        layer.msg(resp.msg);
                }else{
                    layer.alert(resp.msg);
                }
            });
        });
    });
</script>
<script src="/js/scroll.page.js"></script>
</block>