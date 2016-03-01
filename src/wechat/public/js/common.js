/**
 * 以字符串形式执行方法
 * @param func
 * @param args
 * @param defaultValue
 * @returns {*}
 */
var calculateFunctionValue = function (func, args, defaultValue) {
    if (typeof func === 'string') {
        // support obj.func1.func2
        var fs = func.split('.');

        if (fs.length > 1) {
            func = window;
            $.each(fs, function (i, f) {
                func = func[f];
            });
        } else {
            func = window[func];
        }
    }
    if (typeof func === 'function') {
        return func.apply(null, args);
    }
    return defaultValue;
};
String.prototype.len=function(){return this.replace(/[^\x00-\xff]/g,"__").length;}

function ajax_post(url,data){

    var $this = $(this);
    var node_name = $this.get(0).nodeName;
    if(node_name == 'FORM'){
        var sb_btn = $this.find('[type=submit]').prop("disabled", true);
    }else if(node_name == 'BUTTON' || node_name == 'INPUT'){
        var sb_btn = $this;
    }

    var loading = layer.load();
    $.post(url,data,function(resp){
        if(resp.status == '0'){

            if(resp.url != '' && resp.msg == ''){
                //返回url不为空并且消息为空
                window.location = resp.url;
            }else if(resp.msg != '' && resp.url != null && resp.url != '' ){
                //返回信息与url都不为空
                layer.msg(resp.msg,function(){
                    window.location = resp.url;
                });
            }else if(resp.msg != ''){
                //返回消息为空
                layer.msg(resp.msg,function(){
                    calculateFunctionValue($this.attr('success'),[$this,resp],'');
                });
            }else if(resp.msg == '' && resp.url == ''){
                //返回信息与url都为空
                calculateFunctionValue($this.attr('success'),[$this,resp],'');
            }
        }else{
            if(resp.url == null || resp.url == ''){
                layer.alert(resp.msg,function(index){
                    calculateFunctionValue($this.attr('fail'),[$this,resp],'');
                    layer.close(index);
                });
            }else{
                layer.msg(resp.msg,function(){
                    window.location = resp.url;
                });
            }
        }
    },'json').always(function () {
        layer.close(loading);
        sb_btn.prop('disabled',false);
    });
}
function back(){
    if(document.referrer == ''){
        window.location = '/';
    }else if(window.opener != null){
        window.location = opener.location;
    }else{
        history.back();
    }
}
$(document).ready(function(){
	//-----------------------------------------定义和初始化变量----------------------------------------
	var loadBox=$('aside.loadBox');
	//sound
	var soundList={},soundMax=0,soundLoaded=0;
	var btnSound=$('a.btnSound');

	//----------------------------------------页面初始化----------------------------------------
	loadBox.show();
	load_handler();

	//----------------------------------------微信用户登录验证----------------------------------------


	//----------------------------------------加载页面图片----------------------------------------
	function load_handler(){
		//loadBox.show();
		var loader = new PxLoader();
		loader.addImage('images/turn.gif');

		loader.addProgressListener(function(e) {
			//var per=Math.round(e.completedCount/e.totalCount*100);
		});

		loader.addCompletionListener(function() {
			//console.log('页面图片加载完毕');
			//icom.fadeOut(loadBox,500);
			//sound_handler();
			init_handler();
			loader=null;
		});
		loader.start();
	}//end func

	//----------------------------------------页面逻辑代码----------------------------------------
	function init_handler(){
		icom.fadeOut(loadBox,500);
		//search
		$(".header .search").on("click",function(){
			icom.fadeIn($(".bgshadow"));
			$('.searchbox').css({"opacity":0,y:-30}).show();
			$('.searchbox').transition({opacity:1,y:0},200)
		});

		$('.bgshadow').on("click",function(){
			$(".bgshadow").hide()
			$('.searchbox').hide()
		});
		//download
		$('.kejian .download').on("click",function(){
			icom.fadeIn($(".bgshadow2"));
			$('.conform').show();
		});
		//sure & cancel
		$('.conform .sure,.conform .cancel').on("click",function(){
			$(".bgshadow2").hide()
			$('.conform').hide()
		});

	}//end func

    $(document).on('submit','.ajax-form',function(){
        var $this = $(this);
        var flag = calculateFunctionValue($this.attr('before'),[$this],'');
        if(typeof flag == 'boolean' && !flag){
            return false;
        }
        if($this.hasClass('confirm')){
            var _this = this;
            var index = layer.confirm('您确认提交请求？', function(){
                ajax_post.apply(_this,[_this.action,$this.serializeArray()]);
                layer.close(index);
            });
        }else{
            ajax_post.apply(this,[this.action,$this.serializeArray()]);
        }

        return false;
    });


});//end ready

