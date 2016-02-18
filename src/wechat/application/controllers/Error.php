<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author xuebing<406964108@qq.com>
*/
class ErrorController extends MallController {
    //从2.1开始, errorAction支持直接通过参数获取异常
    public function errorAction($exception) {
        //1. assign to view engine
        //$exception = $this->getRequest()->getParam('exception');
        if(php_sapi_name() == 'cli'){
            
	       SeasLog::error($exception->getCode().'--'.$exception->getMessage());
            return false;            
        }
        
        $this->getView()->assign("exception", $exception);
        
        /*Yaf has a few different types of errors*/
        switch($exception->getCode()){
            case 515:
            case 516:
            case 517:
            case 518:
                $this->_pageNotFound();
                break;
            default:
                $this->_unknownError();
        }
        //5. render by Yaf
        die;
    }
    
    private function _pageNotFound(){
//        $response = $this->getResponse();
//        $response->setHeader($this->getRequest()->getServer('SERVER_PROTOCOL'), '404 Not Found');
//        $response->response();
        $this->getResponse()->setBody($this->getView()->render(APP_PATH.'views/404.php'));
        $this->layout->postDispatch($this->getRequest(),$this->getResponse());
        $this->getResponse()->response();
    }
    
    private function _unknownError(){
//        $response = $this -> getResponse();
//        $response->setHeader($this->getRequest()->getServer('SERVER_PROTOCOL'), '500 Internal Server Error' );
//        $response->response();
        $this->error('网站发生问题，请您重新再试或联系网站管理员！');
    }

}
