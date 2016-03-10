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

        $where          = ['AND'=>['a.status'=>BusinessPlanModel::STATUS_OK]];
        $order_by       = 'a.insert_time DESC';
        $page           = intval(I('page',0));

        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;
        $model = new BusinessPlanModel();


        $list = $model->getList($where);
        $this->assign('list',$list);
        $this->assign('is_teacher',true);
//        echo M()->last_query();
//        die;
        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',[
                                        'html'=>$this->render('../plan/ajax.list'),
                                        'list_total'=>count($list),
                                        'page'=>$page+1
                                    ]
                            );
        }

        $this->assign('active_menu','index_teacher');
        $this->getView()->assign('total',intval($model->getListCount($where)));
        $this->getView()->assign('page',$page+1);
    }
}
