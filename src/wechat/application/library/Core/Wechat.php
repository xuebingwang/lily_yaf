<?php
namespace Core;
use \Yaf\Dispatcher;
use \Yaf\Controller_Abstract;
use \Yaf\Registry;
/**
 * Created by PhpStorm.
 * User: xuebingwang
 * Date: 2015/9/29
 * Time: 22:48
 */

class Wechat extends Controller_Abstract{

    protected $config;
    protected $wechat;
    protected $raw_data;

    public function init(){
//         Dispatcher::getInstance()->returnResponse(true);
        Dispatcher::getInstance()->disableView();

        $this->config = Registry::get('config');

        $config_setting = M('t_wechat_setting')->get('*',['id'=>1]);

        $this->wechat = new \Wechat($config_setting);
    }

    public function templateAction(){

        if(!isset($this->raw_data['body']) || empty($this->raw_data['body'])){
            throw new \Exception('{"errcode":10000,"errmsg":"body节点不能为空"}',10000);
        }

        $result = $this->wechat->sendTemplateMessage($this->raw_data['body']);
        if(empty($result)){

            echo json_encode(['errcode'=>$this->wechat->errCode,'errmsg'=>$this->wechat->errMsg]);
        }else{
            echo json_encode($result);
        }
    }

    public function customAction(){

        if(!isset($this->raw_data['body']) || empty($this->raw_data['body'])){
            throw new \Exception('{"errcode":10000,"errmsg":"body节点不能为空"}',10000);
        }

        $result = $this->wechat->sendCustomMessage($this->raw_data['body']);
        if(empty($result)){

            echo json_encode(['errcode'=>$this->wechat->errCode,'errmsg'=>$this->wechat->errMsg]);
        }else{
            echo json_encode($result);
        }
    }
}
