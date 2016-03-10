<?php
use Core\Mall;
/**
 * @name PlanController
 * @author xuebingwang
 * @desc 我的计划书控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PlanController extends Mall {

    public function init(){
        parent::init();
        if(empty($this->user['student_id'])){
            $this->error('请您先做新人报道！');
        }
        if($this->user['student_status'] != StudentModel::STATUS_OK){
            $this->error('对不起，您的状态不正常！');
        }
    }

    /**
     * 我的计划书列表
     */
    public function indexAction(){
        $where = [
            'AND'=>[
                'a.status'=>1,
                'student_id'=>$this->user['user_id']
                ]
        ];
        $order_by = 'a.insert_time DESC';

        $page = intval(I('page',0));

        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $list = M('t_business_plan(a)')->select(
            [
                '[><]t_category(b)'=>['a.category'=>'id'],
                '[>]t_business_plan_count(d)'=>['a.id'=>'plan_id','AND'=>['d.wx_id'=>$this->user['wx_id'],'d.type'=>1]],
            ],
            [
                'a.*',
                'b.title(category_name)',
                'd.wx_id',
            ],
            $where
        );

        $this->assign('list',$list);
//        echo M()->last_query();
//        die;
        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',['html'=>$this->render('ajax.list')]);
        }

        $this->layout->title = '我的商业计划书';
    }

    /**
     *添加计划书
     */
    public function addAction(){
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

            $data['student_id'] = $this->user['user_id'];
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


    /*
     * 名师点评(首页)
     * */
    public function commentAction() {
        //读取商业计划书
        $where = [
            'AND'=>[
                'b.status' => TeacherModel::STATUS_OK,
                'b.user_id'=>$this->user['user_id']
            ]
        ];
        $order_by = 'a.insert_time DESC';

        $page = intval(I('page',0));

        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $list = M('t_business_plan(a)')->select(
            [
                '[>]t_student(b)' => ['a.student_id' => 'id']
            ],
            [
                'a.*',
            ],
            $where
        );

        if(empty($list)) {
            $this->redirect('/member/plan/index');
        }

        $this->assign('list',$list);
        $this->layout->title = '名师点评';
    }
}
