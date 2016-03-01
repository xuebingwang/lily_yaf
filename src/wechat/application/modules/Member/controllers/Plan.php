<?php
use Core\Mall;
/**
 * @name PlanController
 * @author xuebingwang
 * @desc 我的计划书控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PlanController extends Mall {

    /**
     *
     */
    public function addAction(){

        if(empty($this->user['student_id'])){
            $this->error('请您先报道！');
        }
        if(IS_POST){
            $data = [];
            $data['title'] = I('title');
            if(empty($data['title'])){
                $this->error('请填写计划书标题！');
            }
            if(length_regex($data['title'],60)){
                $this->error('计划书标题最大允许输入60个字符！');
            }
            $data['category'] = I('category');
            if(empty($data['category'])){
                $this->error('请选择计划书分类！');
            }
            $data['logo'] = I('logo');
            if(empty($data['logo'])){
                $this->error('请上传计划书封面！');
            }
            $data['file'] = I('plan_file');
            if(empty($data['file'])){
                $this->error('请上传计划书附件！');
            }
            $data['description'] = I('description');
            if(empty($data['description'])){
                $this->error('请上传计划书附件！');
            }
            if(length_regex($data['description'],5000)){
                $this->error('计划书详细描述最大允许输入5000个字符！');
            }

            $data['student_id'] = $this->user['student_id'];
            $data['insert_time'] = time_format();
            if(M('t_business_plan')->insert($data)){
                $this->success('保存成功！',U('/'));
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }

        $cate_list = [];
        foreach(M('t_category')->select(['id','pid','title'],['status'=>1]) as $item){
            $cate_list[$item['pid']][$item['id']] = $item['title'];
        }
        $this->assign('cate_list',$cate_list);
        $this->layout->title = '上传计划书';
    }
}
