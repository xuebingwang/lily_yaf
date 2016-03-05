<?php
use Core\Mall;
/**
 * @name IndexController
 * @author xuebingwang
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class IndexController extends Mall {

    /**
     *
     */
    public function indexAction(){
//        die('aaa');
    }

    public function teacherAction(){

        $where          = ['AND'=>['a.status'=>1]];
        $order_by       = 'a.insert_time DESC';
        $page           = intval(I('page',0));

        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $list = M('t_business_plan(a)')->select(
            [
                '[><]t_category(b)'=>['a.category'=>'id'],
                '[><]t_user(c)'=>['a.student_id'=>'id'],
                '[>]t_business_plan_count(d)'=>['a.id'=>'plan_id','AND'=>['d.wx_id'=>$this->user['wx_id'],'d.type'=>1]],
            ],
            [
                'a.*',
                'b.title(category_name)',
                'c.name(student_name)',
                'd.wx_id',
            ],
            $where
        );

        $this->assign('list',$list);
        $this->assign('is_teacher',true);
//        echo M()->last_query();
//        die;
        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',['html'=>$this->render('../plan/ajax.list')]);
        }
    }
}
