<?php
namespace Core;

use \Yaf\Controller_Abstract;
use \Yaf\Registry;
/**
 * @name MallController
 * @author xuebingwang
 * @desc 商城基控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class Mall extends Controller_Abstract {

    protected $config;
    protected $layout;
    protected $user;
    protected $wechat;

    public function init(){
        $this->config = Registry::get('config');

        $this->user = session('user_auth');

        $config_setting = M('t_wechat_setting')->get('*',['id'=>1]);
        $this->wechat = new \Wechat($config_setting);
        if(!is_not_wx() && empty($this->user)){
            //如果当前浏览器是微信浏览器,并且当前为未登录状态
            //重定向至微信,采用网页授权获取用户基本信息接口获取code
            $forward = urlencode(DOMAIN.$_SERVER['REQUEST_URI']);
            $url = DOMAIN.'/callback/spread.html?forward='.$forward;

            $this->redirect($this->wechat->getOauthRedirect($url,'','snsapi_base'));
        }

        $this->config = \Yaf\Registry::get('config');
        $this->layout = Registry::get('layout');
        $this->layout->user = $this->user;
    }

    /**
     * @param $name
     * @param null $value
     */
    protected function assign($name,$value=null){
        $this->getView()->assign($name,$value);
    }

    /**
     * @return string
     */
    protected function getMCA(){
        return $this->getModule().$this->getController().$this->getAction();
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,1,$jumpUrl,$ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,0,$jumpUrl,$ajax);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param int $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    private function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
        if(IS_AJAX || true === $ajax) {// AJAX提交
            $data           =   is_array($ajax)?$ajax:[];
            $data['msg']    =   $message;
            $data['status'] =   $status;
            $data['url']    =   $jumpUrl;
            $this->ajaxReturn($data);
        }

        $this->assign('jumpUrl',$jumpUrl);
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        $this->assign('status',$status);   // 状态
        $this->assign('message',$message);// 提示信息
        $this->assign('data',is_array($ajax)?$ajax:[]);

        if($status) { //发送成功信息
            //发生错误时候默认停留3秒
            $this->assign('waitSecond',3);
            $body = $this->getView()->render(APP_PATH.'views/error.php');
        }else{
            //默认停留1秒
            $this->assign('waitSecond',3);
            $body = $this->getView()->render(APP_PATH.'views/success.php');
        }

        $this->getResponse()->setBody($body);
        $this->layout->postDispatch($this->getRequest(),$this->getResponse());
        $this->getResponse()->response();
        // 中止执行  避免出错后继续执行
        die;
    }

    /**
     * 页面重定向方法
     * @param string $url
     * @param string $alert_msg
     */
    public function redirect($url,$alert_msg=''){
        if(!empty($alert_msg)){
            session('alert_msg',$alert_msg);
        }
        parent::redirect($url);
        die;
    }

    public function display($action){
        parent::display($action);
        die;
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        if(empty($type)) $type  =   $this->config->ajax->return;
        switch (strtoupper($type)){
        	case 'JSON' :
        	    // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Disposition: inline; filename="resp.json"');
                // Prevent Internet Explorer from MIME-sniffing the content-type:
                header('X-Content-Type-Options: nosniff');
                if (strpos(@$_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                    header('Content-Type:application/json; charset=utf-8');
                } else {
                    header('Content-type: text/plain; charset=utf-8');
                }

        	    exit(json_encode($data,$json_option));
        	case 'XML'  :
        	    // 返回xml格式数据
        	    header('Content-Type:text/xml; charset=utf-8');
        	    exit(xml_encode($data));
        	case 'JSONP':
        	    // 返回JSON数据格式到客户端 包含状态信息
        	    header('Content-Type:application/json; charset=utf-8');
        	    $handler  = $this->config->ajax->jsonp->handler;
        	    exit($handler.'('.json_encode($data,$json_option).');');
        	case 'EVAL' :
        	    // 返回可执行的js脚本
        	    header('Content-Type:text/html; charset=utf-8');
        	    exit($data);
        }
    }

    /**
     * 返回当前模块名
     *
     * @access protected
     * @return string
     */
    protected function getModule()
    {
        return $this->getRequest()->module;
    }

    /**
     * 返回当前控制器名
     *
     * @access protected
     * @return string
     */
    protected function getController()
    {
        return $this->getRequest()->controller;
    }

    /**
     * 返回当前动作名
     *
     * @access protected
     * @return string
     */
    protected function getAction()
    {
        return $this->getRequest()->action;
    }

    protected function getPagination($total, $listRows)
    {
        if($total <= $listRows){
            return false;
        }
        $REQUEST = (array)I('request.');
        $page = new Page($total, $listRows, $REQUEST);
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        return $page->show();
    }
}
