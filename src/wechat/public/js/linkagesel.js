if ("undefined" == typeof XBW || !XBW) var XBW = {};
XBW.index = 0;
XBW.linkage = {
    data : '',
    init : function(options){
        var defaults = {
            root        : 0,          //从哪个开始
            emptyText   : '请选择',     //选择框的提示
            selected    : '',         //默认选中的值
            district    : 'district', //input隐藏域的name
            district_id : 'district', //input隐藏域的id
            sel_wrap    : '<span></span>', //下拉菜单的包围
            change_callback    : function(sel,sel_html){
                sel.parent().nextAll().remove();
                if(sel_html == null){
                    return false;
                }
                sel.parent().after($(options.sel_wrap).html(sel_html));
            }, //下拉菜单的包围
            menu_id     : 'sel_'      //下拉菜单ID
        };
        var options = $.extend(defaults, options);
        options.district_id = options.district_id || options.district;

        if(this.data == ''){
            return false;
        }

        var _self = this;

        _self.buildSelect = function(parents,root){

            var flag = root != null ? true : false;
            root     = flag         ? root : options.root;

            var $this = flag ? $(this) : $(options.selector);

            //选择的节点没有下级时
            if(_self.data[root] == null){
                $(this) .parent().nextAll().remove();
                //options.change_callback($this);
                return false;
            }

            var id = options.menu_id+root+XBW.index;
            XBW.index++;
            var sel_html = '<select name="xbw_linkage[]" id="'+id+'">';
            if(options.emptyText != ''){
                sel_html    += '<option selected value="">'+options.emptyText+'</option>'
            }

            $.each(_self.data[root],function(i,v){
                sel_html += '<option value="'+i+'">'+v+'</option>';
            });

            sel_html    += '</select>';

            if(flag){
                options.change_callback($this,sel_html);
            }else{
                sel_html = '<input id="'+options.district_id+'" type="hidden" name="'+options.district+'" />'+sel_html;
                $this.html($(options.sel_wrap).html(sel_html));
            }

            //为下拉框绑定事件
            $('#'+id).change(function(){
                $('#'+options.district_id).val(this.value);
                //if(root > 1 && this.value == ''){
                //    $(options.selector+' .'+options.district_id).val($(options.selector+' #'+options.menu_id+root).prev().val());
                //}

                //递归
                _self.buildSelect.call(this,parents,this.value);
            }).change();

            //自动选择，并触发事件
            if(parents[root] != null){
                $('#'+id).val(parents[root]).change();
            }
        };

        //设置json数据
        if(typeof(this.data) == "string"){
            $.getJSON(this.data,$.proxy(function(json){

                this.data = json;
                this.buildSelect(this.getParentsKey(options.selected));
            }, this));
        }else if(typeof(this.data) == 'object'){
            this.buildSelect(this.getParentsKey(options.selected));
        };

    },

    //根据k找到值
    findVbyK : function(k){
        if(this.data == ''){
            return false;
        }
        var value = [];
        $.each(this.data,function(i,v){
            if(v[k] != null ){
                value.push(i);
                value.push(v[k]);
                value.push(k);
                return false;
            }
        });
        return value;
    },
    //根据选中值，返回所有父类id
    getParentsKey : function(selected){
        var keys = [];
        if(selected > 0){

            var tmp = this.findVbyK(selected);
            keys[tmp[0]] = tmp[2];

            while(tmp[0] > 0){
                tmp = this.findVbyK(tmp[0]);
                keys[tmp[0]] = tmp[2];
            }
        }
        return keys;
    },
    //根据选中值，返回所有父类名称
    getParentsText : function(selected){
        var text = [];
        if(selected != ''){

            var tmp = this.findVbyK(selected);
            text.push(tmp[1]);

            while(tmp[0] > 0){
                tmp = this.findVbyK(tmp[0]);
                text.push(tmp[1]);
            }
        }
        return text.reverse().join(' ');
    }
};
