<?php
use Core\Mall;
/**
 * @name InfoController
 * @author xuebingwang
 * @desc 会员中心默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class InfoController extends Mall {

    public function init(){
        parent::init();
        if(empty($this->user['student_id'])){
            $this->error('请您先做新人报道！',U('/public/reg'),['btn_text'=>'去报道']);
        }
        if($this->user['student_status'] != StudentModel::STATUS_OK){
            $this->error('对不起，您的状态不正常！');
        }

        $this->assign('user',$this->user);
    }

    /**
     * 修改个人信息，学生
     */
    public function editAction(){

        $model = new StudentModel();

        if(IS_POST){
            $student_data['company'] = I('company');
            if(empty($student_data['company'])){
                $this->error('公请输入司名称！');
            }
            if(length_regex($student_data['company'],20)){
                $this->error('公司名称最大允许输入20个字符！');
            }

            $student_data['company_industry'] = intval(I('company_industry'));
            $student_data['company_scale'] = intval(I('company_scale'));

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

            $user_model = new UserModel();
            if($user_model->update($data,['id'=>$this->user['user_id']]) && $model->setId($this->user['student_id'])->update($student_data)){
                $this->user['name'] = $data['name'];
                $this->user['mobile'] = $data['mobile'];
                session('user_auth',$this->user);

                $this->success('保存成功！');
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }

        $item = $model->setId($this->user['wx_id'])->getItem();

        $this->assign('item',$item);

        $cate_list = [];
        foreach(M('t_category')->select(['id','pid','title'],['status'=>1]) as $item){
            $cate_list[$item['pid']][$item['id']] = $item['title'];
        }
        $this->assign('cate_list',$cate_list);

        $this->layout->title = '我的资料';
    }

    /**
     * 我的-学生端
     */
    public function indexAction(){

        $this->layout->title = '我的';
    }
}
