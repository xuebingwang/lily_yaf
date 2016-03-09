<?php
namespace Admin\Controller;
use Admin\Service\ApiService;
use User\Api\UserApi;
/**
 * 微信自定义菜单管理
 *
 * @author 保灵
 */
class WxmenuController extends AdminController
{
    private $_menu;
    private $_api;
    public function _initialize()
    {
        parent::_initialize();
        $this->_api = new ApiService();
        $resp = $this->_api->setApiUrl(C('APIURI.wechat'))
                    ->setData()
                    ->send('wechat/menu/getMenu');
        if(empty($resp) || $resp['errcode'] != '0'){
            $this->error('哎呀，出错了，请重新再试或联系管理员！');
        }
        
        $this->_menu = $this->_getTree($resp['data']['menu']['button']);
        
    }

    public function index()
    {
        $this->assign('tree', $this->_menu);
        $this->meta_title = '自定义菜单列表';
        $this->display();
    }
    
    public function add($pid = 0)
    {
        if(IS_POST){ //提交表单
            $pid = I('pid');
            if(!trim(I('name'))){
                $this->error('菜单名称不能为空！');
            }
            if(!trim(I('url'))){
                $this->error('跳转URL不能为空！');
            }
            if($pid && !isset($this->_menu[$pid])){
                $this->error('没有指定的父级菜单！');
            }
            if(!$pid && count($this->_menu) >= 3){
                $this->error('自定义菜单最多包括3个一级菜单！');
            }
            if($pid && count($this->_menu[$pid]['_']) >= 5){
                $this->error('每个一级菜单最多包含5个二级菜单！');
            }
            
            $sub_button = [ 'type' => 'view', 'name' => trim(I('name')), 'url' => trim(I('url')), 'sub_button' => []];
            if($pid){
                unset($this->_menu[$pid]['type']);
                unset($this->_menu[$pid]['url']);
                $this->_menu[$pid]['_'][] = $sub_button;
            }else{
                $this->_menu['_'][] = $sub_button;
            }
            $newmenu = $this->_return_reset_menu('add');
            $this->_createMenu($newmenu);
        } else {
            $parent = isset($this->_menu[$pid]) ? $this->_menu[$pid] : array();
            
            $this->assign('parent', $parent);
            $this->meta_title = '添加菜单';
            $this->display();
        }
    }
    
    public function edit($id = null, $pid = 0)
    {
        if(IS_POST){
            if(!trim(I('name'))){
                $this->error('菜单名称不能为空！');
            }
            if(I('type') == 'view' & !trim(I('url'))){
                $this->error('跳转URL不能为空！');
            }
            $newmenu = $this->_return_reset_menu();
            $this->_createMenu($newmenu);
        }else{
            $parent = isset($this->_menu[$pid]) ? $this->_menu[$pid] : array();
            $menu = $parent ? $parent['_'][$id] : $this->_menu[$id];
            $this->assign('parent', $parent ? $parent['name'] : '顶级菜单');
            $this->assign('info', $menu);
            $this->meta_title = '编辑菜单';
            $this->display();
        }
        
    }
    
    public function remove($id = null, $pid = 0){
        if(!$pid){
            if(isset($this->_menu[$id])){
                unset($this->_menu[$id]);
            }
        }else{
            if(isset($this->_menu[$pid]) && isset($this->_menu[$pid]['_'][$id])){
                unset($this->_menu[$pid]['_'][$id]);
            }
        }
        $newmenu = $this->_return_reset_menu('remove');
        $this->_createMenu($newmenu);
    }
    
    public function tree($tree = null)
    {
        $this->assign('tree', $tree);
        $this->display('tree');
    }
    
    private function _createMenu($menu)
    {
        $resp = $this->_api->setApiUrl(C('APIURI.wechat'))
                    ->setData($menu)
                    ->send('wechat/menu/createMenu');
        if(empty($resp) || $resp['errcode'] != '0'){
            $this->error('哎呀，出错了，请重新再试或联系管理员！');
        }else{
            $this->success('菜单更新成功！',U('index'));
        }
    }

    private function _getTree($list)
    {
        $result = array();
        foreach ($list as $pk => $pv)
        {
            $id = $pk + 1;
            $result[$id] = $pv;
            $result[$id]['id'] = $id;
            if($pv['sub_button']){
                unset($result[$id]['sub_button']);
                foreach ($pv['sub_button'] as $k => $v)
                {
                    $cid = $id . $k;
                    $result[$id]['_'][$cid] = $v;
                    $result[$id]['_'][$cid]['id'] = $cid;
                    $result[$id]['_'][$cid]['pid'] = $id;
                }
            }
        }
        return $result;
    }
    
    private function _return_reset_menu($type = 'edit')
    {
        $result = array();
        if($type == 'edit'){
            $pid = I('pid');$id = I('id');
            if(!$pid){
                $this->_menu[$id]['name'] = trim(I('name'));
                $this->_menu[$id]['url'] = trim(I('url'));
            }else{
                $this->_menu[$pid]['_'][$id]['name'] = trim(I('name'));
                $this->_menu[$pid]['_'][$id]['url'] = trim(I('url'));
            }
        }
        
        $this->_menu = array_values($this->_menu);
        foreach ($this->_menu as $key => $menu)
        {
            $result[$key] = $menu;
            unset($result[$key]['id']);
            if(isset($result[$key]['_']) && $result[$key]['_']){
                unset($result[$key]['_']);
                $result[$key]['sub_button'] = array_values($menu['_']);
                
                foreach ($result[$key]['sub_button'] as $ck => $cv)
                {
                    unset($result[$key]['sub_button'][$ck]['pid']);
                    unset($result[$key]['sub_button'][$ck]['id']);
                }
            }
            
        }
        return $result;
    }
    
}
