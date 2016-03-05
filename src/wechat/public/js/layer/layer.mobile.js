/*!

 @Name：layer mobile v1.7 弹层组件移动版
 @Author：贤心
 @Date：2015-11-25
 @Copyright：Sentsin Xu(贤心)
 @官网：http://layer.layui.com/mobile/
 @License：MIT

 */

;!function(win){
"use strict";

var doc = document, query = 'querySelectorAll', claname = 'getElementsByClassName', S = function(s){
    return doc[query](s);
};

//默认配置
var config = {
     type: 0,
     shade: true,
     shadeClose: true,
     fixed: true,
     anim: true
};

var ready = {
    extend: function(obj){
        var newobj = JSON.parse(JSON.stringify(config));
        for(var i in obj){
            newobj[i] = obj[i];
        }
        return newobj;
    },
    title:'提示',
    btn: ['&#x786E;&#x5B9A;','&#x53D6;&#x6D88;'],
    timer: {}, end: {}
};

//点触事件
ready.touch = function(elem, fn){
    var move;
    if(!/Android|iPhone|SymbianOS|Windows Phone|iPad|iPod/.test(navigator.userAgent)){
        return elem.addEventListener('click', function(e){
            fn.call(this, e);
        }, false);
    }
    elem.addEventListener('touchmove', function(){
        move = true;
    }, false);
    elem.addEventListener('touchend', function(e){
        e.preventDefault();
        move || fn.call(this, e);
        move = false;
    }, false);
};

var index = 0, classs = ['layermbox'], Layer = function(options){
    var that = this;
    that.config = ready.extend(options);
    that.view();
};

Layer.prototype.view = function(){
    var that = this, config = that.config, layerbox = doc.createElement('div');

    that.id = layerbox.id = classs[0] + index;
    layerbox.setAttribute('class', classs[0] + ' ' + classs[0]+(config.type || 0));
    layerbox.setAttribute('index', index);

    var title = (function(){
        var titype = typeof config.title === 'object';
        return config.title
        ? '<div class="weui_dialog_hd" style="'+ (titype ? config.title[1] : '') +'"><strong class="weui_dialog_title">'+ (titype ? config.title[0] : config.title)  +'</strong></div>'
        : '';
    }());

    var button = (function(){
        var btns = (config.btn || []).length, btndom;
        if(btns === 0 || !config.btn){
            return '';
        }
        btndom = '<a type="1" href="javascript:" class="weui_btn_dialog primary">'+ config.btn[0] +'</a>'
        if(btns === 2){
            btndom = '<a type="0" href="javascript:" class="weui_btn_dialog default">'+ config.btn[1] +'</a>' + btndom;
        }
        return '<div class="weui_dialog_ft">'+ btndom + '</div>';
    }());

    if(!config.fixed){
        config.top = config.hasOwnProperty('top') ?  config.top : 100;
        config.style = config.style || '';
        config.style += ' top:'+ ( doc.body.scrollTop + config.top) + 'px';
    }

    if(config.type === 3){

        layerbox.innerHTML =    (config.shade ? '<div '+ (typeof config.shade === 'string' ? 'style="'+ config.shade +'"' : '') +' class="weui_mask weui_mask_transparent"></div>' : '') +
                                '<div class="weui_loading_toast weui_toast">' +
                                    '<div class="weui_loading">' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_0"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_1"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_2"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_3"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_4"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_5"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_6"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_7"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_8"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_9"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_10"></div>' +
                                        '<div class="weui_loading_leaf weui_loading_leaf_11"></div>' +
                                    '</div>' +
                                    '<p class="weui_toast_content">加载中</p>' +
                                '</div>';
    }else if(config.type == 2){

        layerbox.innerHTML = (config.shade ? '<div '+ (typeof config.shade === 'string' ? 'style="'+ config.shade +'"' : '') +' class="weui_mask weui_mask_transparent"></div>' : '') +
                             '<div class="weui_toast">' +
                                    '<i class="weui_icon_toast"></i>' +
                                '<p class="weui_toast_content">'+config.content+'</p>' +
                             '</div>';
    }else{
        layerbox.innerHTML = (config.shade ? '<div '+ (typeof config.shade === 'string' ? 'style="'+ config.shade +'"' : '') +' class="weui_mask"></div>' : '')
                                +'<div class="weui_dialog" '+ (!config.fixed ? 'style="position:static;"' : '') +'>'
                                        + title
                                    +'<div class="layermchild '+ (config.className ? config.className : '') +' '+ ((!config.type && !config.shade) ? 'layermborder ' : '') + (config.anim ? 'layermanim' : '') +'" ' + ( config.style ? 'style="'+config.style+'"' : '' ) +'>'
                                    +'<div class="weui_dialog_bd">'+ config.content +'</div>'
                                        + button
                                    +'</div>'
                                +'</div>';
    }



    if(!config.type || config.type === 3){
        var dialogs = doc[claname](classs[0] + config.type), dialen = dialogs.length;
        if(dialen >= 1){
            layer.close(dialogs[0].getAttribute('index'))
        }
    }

    document.body.appendChild(layerbox);
    var elem = that.elem = S('#'+that.id)[0];
    config.success && config.success(elem);

    that.index = index++;
    that.action(config, elem);
};

Layer.prototype.action = function(config, elem){
    var that = this;
    //自动关闭
    if(config.time){
        ready.timer[that.index] = setTimeout(function(){
            layer.close(that.index);
        }, config.time*1000);
    }

    //关闭按钮
    //if(config.title){
    //    var end = elem[claname]('layermend')[0], endfn = function(){
    //        config.cancel && config.cancel();
    //        layer.close(that.index);
    //    };
    //    ready.touch(end, endfn);
    //}

    //确认取消
    var btn = function(){
        var type = this.getAttribute('type');
        if(type == 0){
            config.no && config.no();
            layer.close(that.index);
        } else {
            config.yes ? config.yes(that.index) : layer.close(that.index);
        }
    };
    if(config.btn){
        var btns = elem[claname]('weui_dialog_ft')[0].children, btnlen = btns.length;
        for(var ii = 0; ii < btnlen; ii++){
            ready.touch(btns[ii], btn);
        }
    }

    //点遮罩关闭
    if(config.shade && config.shadeClose){
        var shade = elem[claname]('weui_mask')[0];
        ready.touch(shade, function(){
            layer.close(that.index, config.end);
        });
    }

    config.end && (ready.end[that.index] = config.end);
};

win.layer = {
    v: '1.7',
    index: index,

    //核心方法
    open: function(options){
        var o = new Layer(options || {});
        return o.index;
    },
    //各种快捷引用
    alert: function(content, options, yes){
        var type = typeof options === 'function';
        if(type) yes = options;

        return layer.open($.extend({
            title:ready.title,
            btn:['确定'],
            content: content,
            yes: yes
        }, type ? {} : options));
    },

    confirm: function(content, options, yes, cancel){
        var type = typeof options === 'function';
        if(type){
            cancel = yes;
            yes = options;
        }

        return layer.open($.extend({
            title:ready.title,
            content: content,
            shadeClose: false,
            btn: ready.btn,
            yes: yes,
            no: cancel
        }, type ? {} : options));
    },
    msg: function(content, options, end){ //最常用提示层

        var type = typeof options === 'function';
        if(type) end = options;
        return layer.open($.extend({
            content: content,
            time: 3,
            title: false,
            end: end,
            shade: 'opacity:0.1',
            type: 2
        }, type ? {} : function(){
            options = options || {};
            if(options.icon === -1 || options.icon === undefined){
                options.icon = options.icon || 'test';
            }
            return options;
        }()));
    },

    load: function(icon, options){
        return layer.open($.extend({
            type: 3,
            time: 3,
            shade: 'opacity:0.1'
        }, options));
    },

    close: function(index){
        var ibox = S('#'+classs[0]+index)[0];
        if(!ibox) return;
        ibox.innerHTML = '';
        doc.body.removeChild(ibox);
        clearTimeout(ready.timer[index]);
        delete ready.timer[index];
        typeof ready.end[index] === 'function' && ready.end[index]();
        delete ready.end[index];
    },

    //关闭所有layer层
    closeAll: function(){
        var boxs = doc[claname](classs[0]);
        for(var i = 0, len = boxs.length; i < len; i++){
            layer.close((boxs[0].getAttribute('index')|0));
        }
    }
};

'function' == typeof define ? define(function() {
    return layer;
}) : function(){

    var js = document.scripts, script = js[js.length - 1], jsPath = script.src;
    var path = jsPath.substring(0, jsPath.lastIndexOf("/") + 1);

    //如果合并方式，则需要单独引入layer.css
    if(script.getAttribute('merge')) return;
    //
    //document.head.appendChild(function(){
    //    var link = doc.createElement('link');
    //    link.href = path + 'need/layer.css';
    //    link.type = 'text/css';
    //    link.rel = 'styleSheet'
    //    link.id = 'layermcss';
    //    return link;
    //}());

}();

}(window);