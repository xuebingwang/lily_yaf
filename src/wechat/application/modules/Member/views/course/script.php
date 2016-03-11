<block name="script">
    <script>
        $(function(){
            $(document).on('click','.down-file',function(){
                var $this = $(this);
                $.getJSON('<?=U('/plan/downFile')?>',{id:$this.attr('item_id')},function(resp){
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