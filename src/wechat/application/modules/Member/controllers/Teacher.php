<?php
use Core\Mall;
/**
 * @name TeacherController
 * @author xuebingwang
 * @desc 老师控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class TeacherController extends Mall {

    public function init(){
        parent::init();
        if($this->user['is_teacher'] != UserModel::BOOL_YES){
            $this->error('您需要先通过名师认证才能点评！',U('/public/regTeacher'),['btn_text'=>'去认证']);
        }
        $this->assign('user',$this->user);
    }


    public function editAction(){

        if(IS_POST){
            $data = [];
            $data['company'] = I('company');
            if(empty($data['company'])){
                $this->error('公请输入司名称！');
            }
            if(length_regex($data['company'],20)){
                $this->error('公司名称最大允许输入20个字符！');
            }

            $data['company_industry'] = intval(I('company_industry'));
            $data['company_scale'] = intval(I('company_scale'));

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('请输入手机号码！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }

            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('请输入姓名！');
            }
            if(length_regex($data['name'],20)){
                $this->error('姓名最大允许输入20个字符！');
            }
            $data['update_time'] = time_format();
            if(M('t_user')->update($data,['id'=>$this->user['user_id']])){
                $this->success('保存成功！');
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }
        $item = M('t_user')->get('*',['id'=>$this->user['user_id']]);

        $this->assign('item',$item);

        $cate_list = [];
        foreach(M('t_category')->select(['id','pid','title'],['status'=>1]) as $item){
            $cate_list[$item['pid']][$item['id']] = $item['title'];
        }
        $this->assign('cate_list',$cate_list);

        $this->layout->title = '我的资料';
    }

    /**
     *
     */
    public function indexAction(){

        $this->layout->title = '我的';
        $this->assign('is_teacher',true);
        $this->getResponse()->setBody($this->render('../info/index'));
        return false;
    }
}
