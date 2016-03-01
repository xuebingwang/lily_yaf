<?php
use Core\Mall;
/**
 * @name PublicController
 * @author xuebingwang
 * @desc Public控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PublicController extends Mall {

    public function regAction(){
        if(!empty($this->user['student_id'])){
            $this->error('您已经报道过了！');
        }

        if(IS_POST){
            $data['company'] = I('company');
            if(empty($data['company'])){
                $this->error('公司信息不能为空！');
            }
            if(length_regex($data['company'],20)){
                $this->error('公司信息最大允许输入20个字符！');
            }

            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('姓名不能为空！');
            }
            if(length_regex($data['name'],20)){
               $this->error('姓名最大允许输入20个字符！');
            }

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('公司信息不能为空！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }

            $data['wx_id'] = $this->user['userid'];
            $data['insert_time'] = time_format();
            $this->user['student_id'] = M('t_student')->insert($data);
            if(!empty($this->user['student_id'])){
                $this->user['student_name'] = $data['name'];
                $this->user['student_mobile'] = $data['mobile'];
                $this->user['student_company'] = $data['company'];

                $this->success('报道成功！',U('/'));
            }else{
                $this->error('报道失败，请重新再试或联系客服人员！');
            }
        }

        $this->layout->title = '新人报道';
    }


}
