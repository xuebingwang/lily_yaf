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
		})

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
		})




	}//end func



});//end ready