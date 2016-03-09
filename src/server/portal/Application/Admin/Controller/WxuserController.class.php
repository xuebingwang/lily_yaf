<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Service\ApiService;
use User\Api\UserApi;

/**
* 微信用户信息
*
*/
class WxuserController extends AdminController{
	/**
	*  获取用户列表
	*/
	public function index(){

		$nickname  =  I('nickname');
        $map['sex']  =   array('egt',0);
        if(is_numeric($nickname)){
            $map['id|nickname']  =  array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }elseif(!empty($nickname)){
            $map['nickname']   =   array('like', '%'.(string)$nickname.'%');
        }

        $prefix = C('DB_PREFIX');
        $model = M()->table($prefix.'wx_user a');
        $list   = $this->lists($model, $map,"subscribe_time desc",'a.*');
        //echo  $model->_sql();
        $this->assign('_list', $list);
        $this->meta_title = '用户列表';
        $this->display();
	}


	/**
     * 查看详情
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
    	$id = I('get.id');
        empty($id) && $this->error('参数错误！');

        $info = M('wx_user')->field(true)->find($id);

        $this->assign('info', $info);
        $this->meta_title = '查看详情';
        $this->display();
    }
}