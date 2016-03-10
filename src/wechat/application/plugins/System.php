<?php
// application\plugins\System.php
// Yaf_Registry::get('config')->application->url_suffix为配置文件定义的后缀，如：.html

class SystemPlugin extends Yaf\Plugin_Abstract {
    
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        if(Yaf\Registry::get('config')->application->url_suffix) {
            if(!isset($_SERVER['REQUEST_URI'])){
            	return false;
            }
                
            $url = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
            if(strtolower(substr($url, - strlen(Yaf\Registry::get('config')->application->url_suffix))) == strtolower(Yaf\Registry::get('config')->application->url_suffix)) {
                $request->setRequestUri(substr($url, 0 , - strlen(Yaf\Registry::get('config')->application->url_suffix)));
            }
        }
    }
}