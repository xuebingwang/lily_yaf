<?php

class LayoutPlugin extends Yaf\Plugin_Abstract {

    private $layoutDir;
    private $layoutFile;

    public $layoutVars = [];
    public $block = [];

    public function __construct($layoutFile, $layoutDir=null){
        $this->layoutFile = $layoutFile;
        $this->layoutDir = ($layoutDir) ? $layoutDir : APP_PATH.'views';
    }

    public function setLayoutFile($layoutFile){

        $this->layoutFile = $layoutFile;
    }

    public function  __set($name, $value) {
        $this->layoutVars[$name] = $value;
    }

    public function postDispatch ( Yaf\Request_Abstract $request , Yaf\Response_Abstract $response ){
        /* get the body of the response */
        $body = $response->getBody();
        /*clear existing response*/
        $response->clearBody();

        preg_replace_callback('/<block\sname=[\'"](.+?)[\'"]\s*?>(.*?)<\/block>/is', array($this, 'parseBlock'),$body);

        /* wrap it in the layout */
        $view = new \Yaf\View\Simple($this->layoutDir);
        $view->content = str_replace($this->block,[''],$this->replaceBlock($body));

        foreach($this->layoutVars as $name=>$value){
            $view->assign($name,$value);
        }

        /* set the response to use the wrapped version of the content */
        $response->setBody($this->replaceBlock($view->render($this->layoutFile)));
    }

    /**
     * 记录当前页面中的block标签
     * @access private
     * @param string $name block名称
     * @param string $content  模板内容
     * @return string
     */
    private function parseBlock($name,$content = '') {
        if(is_array($name)){
            $content = $name[2];
            $name    = $name[1];
        }

        $this->block[$name] = $content;
    }

    /**
     * 替换继承模板中的block标签
     * @access private
     * @param string $content  模板内容
     * @return string
     */
    private function replaceBlock($content){
        static $parse = 0;
        $begin = '<';
        $end   = '>';
        $reg   = '/('.$begin.'block\sname=[\'"](.+?)[\'"]\s*?'.$end.')(.*?)'.$begin.'\/block'.$end.'/is';
        if(is_string($content)){
            do{
                $content = preg_replace_callback($reg, array($this, 'replaceBlock'), $content);
            } while ($parse && $parse--);
            return $content;
        } elseif(is_array($content)){
            if(preg_match('/'.$begin.'block\sname=[\'"](.+?)[\'"]\s*?'.$end.'/is', $content[3])){ //存在嵌套，进一步解析
                $parse = 1;
                $content[3] = preg_replace_callback($reg, array($this, 'replaceBlock'), "{$content[3]}{$begin}/block{$end}");
                return $content[1] . $content[3];
            } else {
                $name    = $content[2];
                $content = $content[3];
                $content = isset($this->block[$name]) ? $this->block[$name] : $content;
                return $content;
            }
        }
    }
}
