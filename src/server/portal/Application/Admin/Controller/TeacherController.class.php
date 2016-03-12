<?php
/**
 * Created by PhpStorm.
 * User: xiongxin
 * Date: 2016/3/11
 * Time: 19:11
 */
namespace Admin\Controller;

class TeacherController extends AdminController {
    /**
     *  获取用户列表
     */
    public function index(){
        $nickname  =  I('nickname');
        $map['a.status']  =   array('eq',"OK#");
        if(is_numeric($nickname)){
            $map['id|nickname']  =  array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }elseif(!empty($nickname)){
            $map['nickname']   =   array('like', '%'.(string)$nickname.'%');
        }

        $prefix = C('DB_PREFIX');
        $model = M()->table($prefix.'teacher a')->join("left join t_user on a.user_id=t_user.id");
        $list   = $this->lists($model, $map,"insert_time desc",'a.*,t_user.name');
        $this->assign('_list', $list);
        $this->meta_title = '用户列表';
        $this->display();
    }

    /**
     * 修改老师审核状态
     */
    public function status() {
        if (IS_AJAX) {
            $teacher = M("teacher"); // 实例化User对象
            //// 要修改的数据对象属性赋值
            $data['apply_status'] = I('status');
            $arr = [];
            if($teacher->where('id=' . I('id'))->save($data)){
                $arr['status'] = 1;
                $arr['msg'] = '审核成功！';
                $arr['value'] = I('status');
            } else {
                $arr['status'] = -1;
                $arr['msg'] = '审核失败！';
            }
            $this->ajaxReturn($arr);
        }
    }

    /**
     * 查看详情
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数错误！');

        $info = M('teacher')->field(true)->find($id);
        $name = M('user')->field('name,wx_id')->find($info['user_id']);
        $wx_info = M('wx_user')->field(true)->find($name['wx_id']);
        $this->assign('info', $info);
        $this->assign('name', $name);
        $this->assign('wx_info', $wx_info);
        $this->meta_title = '查看详情';
        $this->display();
    }
}