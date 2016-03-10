<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class BusinessPlanModel extends Model{

    public $table = 't_business_plan(a)';

    /**
     * 正常状态
     */
    const STATUS_OK = 'OK#';

    /**
     * 禁用状态
     */
    const STATUS_DIS = 'DIS';

    /**
     * 删除状态
     */
    const STATUS_DEL = 'DEL';

    /**
     * @param $where
     * @return bool
     */
    public function getList($where){
        $user = session('user_auth');
        return $this->select(
            [
                '[><]t_category(b)'=>['a.category'=>'id'],
                '[><]t_user(c)'=>['a.student_id'=>'id'],
                '[>]t_business_plan_count(d)'=>['a.id'=>'plan_id','AND'=>['d.wx_id'=>$user['wx_id'],'d.type'=>1]],
            ],
            [
                'a.*',
                'b.title(category_name)',
                'c.name(student_name)',
                'd.wx_id',
            ],
            $where
        );
    }

    /**
     * @param $where
     * @return int
     */
    public function getListCount($where){

        if(isset($where['LIMIT'])){
            unset($where['LIMIT']);
        }
        return $this->count(
            [
                '[><]t_category(b)'=>['a.category'=>'id'],
                '[><]t_user(c)'=>['a.student_id'=>'id'],
            ],
            '*',
            $where
        );
    }
}
