<?php
/**
 * 自定义菜单管理
 *
 * @author 保灵
 */

class MenuController extends Core\Wechat{

    public function init(){
        parent::init();
        $this->raw_data = json_to_array(file_get_contents('php://input'));
    }

    public function getMenuAction()
    {
        echo json_encode(['errcode'=>0,'errmsg'=>'成功！','data'=>  $this->wechat->getMenu()]);
        die;
    }
    
    public function createMenuAction()
    {
        $menus = $this->raw_data['body'];
        foreach ($menus as $key => $menu)
        {
            if(isset($menu['url']) && preg_match("#^http:\/\/(lily)\.(mi360)\.(me)(.*?)#", $menu['url'])){
                $menus[$key]['url'] = $this->wechat->getOauthRedirect($menu['url'],'index');
            }
            if(isset($menu['sub_button']) && is_array($menu['sub_button']))
            foreach ($menu['sub_button'] as $k => $v)
            {
                if(isset($v['url']) && preg_match("#^http:\/\/(lily)\.(mi360)\.(me)(.*?)#", $menu['url'])){
                    $menus[$key]['sub_button'][$k]['url'] = $this->wechat->getOauthRedirect($v['url'], 'index');
                }
            }
        }
        
        if($this->wechat->createMenu(['button' => $menus])){
            echo json_encode(['errcode'=>0,'errmsg'=>'成功！']);
        }else{
            echo json_encode(['errcode'=>-1,'errmsg'=>'失败！', 'menu' => $menus]);
        }
        die;
    }
    
}
