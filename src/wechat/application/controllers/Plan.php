<?php
use Core\Mall;
/**
 * @name PlanController
 * @author xuebingwang
 * @desc 商业计划书详情控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PlanController extends Mall {

    public function indexAction(){

        $where = ['status'=>1];

        $order = I('order','view_count DESC');

        $page = intval(I('page',0));
        $list = M('t_business_plan')->select('*',
            [
                'AND'=>$where,
                'LIMIT'=>[$page*$this->config->application->pagenum,$this->config->application->pagenum],
                'ORDER'=>$order
            ]
        );

        echo M()->last_query();
        var_dump($list);die;
    }
}
