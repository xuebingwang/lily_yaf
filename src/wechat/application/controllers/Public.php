<?php
use Core\Mall;
/**
 * @name PublicController
 * @author xuebingwang
 * @desc Public控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PublicController extends Mall {

    public function init(){
        parent::init();
        $this->assign('user',$this->user);
    }
    /**
     * 新人报道（学生）
     */
    public function regAction(){
        if($this->user['is_student'] == UserModel::BOOL_YES){
            $this->error('您已经报道过了！');
        }

        if(IS_POST){
            $data['company'] = I('company');
            if(empty($data['company'])){
                $this->error('请输入公司名称！');
            }
            if(length_regex($data['company'],20)){
                $this->error('公司名称最大允许输入20个字符！');
            }

            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('请输入姓名！');
            }
            if(length_regex($data['name'],20)){
               $this->error('姓名最大允许输入20个字符！');
            }

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('请输入手机号码！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }

            if(empty($this->user['user_id'])){
                //创建
                $data['headimgurl'] = $this->user['headimgurl'];
                $data['wx_id']      = $this->user['wx_id'];
            }else{
                //修改
                $data['id']     = $this->user['user_id'];
            }

            $data['is_student'] = UserModel::BOOL_YES;
            $model              = new UserModel();
            $this->user['user_id'] = $model->save($data);
            if(!empty($this->user['user_id'])){
                $this->user['name'] = $data['name'];
                $this->user['mobile'] = $data['mobile'];
                $this->user['company'] = $data['company'];
                $this->user['is_student'] = $data['is_student'];

                session('user_auth',$this->user);

                $this->success('报道成功！',U('/'));
            }else{
                $this->error('报道失败，请重新再试或联系客服人员！');
            }
        }

        $this->layout->title = '新人报道';
    }

    /**
     *  老师认证
     */
    public function regTeacherAction(){
        if($this->user['is_teacher'] == UserModel::BOOL_YES){
            $this->error('您已经通过认证了！');
        }

        if($this->user['teacher_apply_status'] == UserModel::TEACHER_APPLY_STATUS_WAT){
            $this->error('您的资料正在审核中，请勿重复提交！');
        }

        if(IS_POST){

            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('请输入姓名！');
            }
            if(length_regex($data['name'],20)){
                $this->error('姓名最大允许输入20个字符！');
            }

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('请输入手机号码！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }
            $data['good_at'] = I('good_at');
            if(empty($data['good_at'])){
                $this->error('请输入公司名称！');
            }
            if(length_regex($data['good_at'],20)){
                $this->error('擅长最大允许输入20个字符！');
            }
            $data['description'] = I('description');
            if(empty($data['description'])){
                $this->error('不能为空！');
            }
            if(length_regex($data['description'],500)){
                $this->error('个人最大允许输入500个字符！');
            }

            if(empty($this->user['user_id'])){
                //创建
                $data['headimgurl'] = $this->user['headimgurl'];
                $data['wx_id']      = $this->user['wx_id'];
            }else{
                //修改
                $data['id']     = $this->user['user_id'];
            }
            $data['teacher_apply_status'] = UserModel::TEACHER_APPLY_STATUS_WAT;

            $model = new UserModel();
            $this->user['user_id'] = $model->save($data);
            if(!empty($this->user['user_id'])){

                $this->success('提交成功，我们会在1~3个工作日内回复！',U('/'));
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }

        $this->layout->title = '名师认证';
    }
}
