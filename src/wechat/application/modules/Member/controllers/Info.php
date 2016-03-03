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
            $this->error('请您先做新人报道！');
        }
        $this->assign('user',$this->user);
    }


    public function editAction(){

        if(IS_POST){
            $data = [];
            $data['company'] = I('company');
            if(empty($data['company'])){
                $this->error('公司名称不能为空！');
            }
            if(length_regex($data['company'],20)){
                $this->error('公司名称最大允许输入20个字符！');
            }

            $data['company_industry'] = intval(I('company_industry'));
            $data['company_scale'] = intval(I('company_scale'));

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('手机号码不能为空！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }

            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('姓名不能为空！');
            }
            if(length_regex($data['name'],20)){
                $this->error('姓名最大允许输入20个字符！');
            }
            $data['update_time'] = time_format();
            if(M('t_student')->update($data,['id'=>$this->user['student_id']])){
                $this->success('保存成功！');
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }
        $item = M('t_student')->get('*',['id'=>$this->user['student_id']]);

        $this->assign('item',$item);

        $cate_list = [];
        foreach(M('t_category')->select(['id','pid','title'],['status'=>1]) as $item){
            $cate_list[$item['pid']][$item['id']] = $item['title'];
        }
        $this->assign('cate_list',$cate_list);

        $this->layout->title = '我的资料';
    }

    /**
     * 修改头像
     */
    public function editHeadLogoAction(){

        $this->user['head_logo']= I('source');
        if(empty($this->user['head_logo'])){
            $this->error('头像修改失败，地址为空！');
        }
        if(M('t_student')->update(['headimgurl'=>$this->user['head_logo']],['id'=>$this->user['student_id']])){

            session('user_auth',$this->user);
            $this->success('修改成功！',imageView2($this->user['head_logo'],100,100));
        }else{
            $this->error('头像保存失败，请重新再试或联系客服人员！');
        }

    }

    /**
     *
     */
    public function indexAction(){

        $this->layout->title = '我的';
    }
}
