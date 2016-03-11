<?php
/**
 * Created by PhpStorm.
 * User: xiongxin
 * Date: 16/3/10
 * Time: 下午6:16
 */

class CourseModel extends Model{

    public $table = 't_teacher_course(a)';

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

    /** with count
     * @param $where
     * @return bool
     */
    public function getList($where){
        $user = session('user_auth');
        return $this->select(
            [
                '[>]t_category(b)'=>['a.category'=>'id'],
                '[>]t_user(c)' => ['a.user_id' => 'id'],
                '[>]t_teacher_course_count(d)'=>['a.id'=>'course_id','AND'=>['d.wx_id'=>$user['wx_id'],'d.type'=>1]],
            ],
            [
                'a.*',
                'c.name',
                'b.title(category_name)',
                'd.wx_id',
            ],
            $where
        );
    }

    /**
     * 获取单个course
     */
    public function getCourse($where) {
        return $this->get(
            [
                '[>]t_category(b)'=>['a.category'=>'id'],
                '[>]t_user(c)' => ['a.user_id' => 'id'],
            ],
            [
                'a.*',
                'b.title(category)',
                'c.*'
            ],
            $where
        );
    }

    /** no count
     * @param $where
     * @return bool
     */
    public function getList2($where){
        return $this->select(
            [
                '[>]t_category(b)'=>['a.category'=>'id'],
                '[>]t_user(c)' => ['a.user_id' => 'id']
            ],
            [
                'a.*',
                'b.title(category_name)',
                'c.name'
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
                '[>]t_category(b)'=>['a.category'=>'id'],
            ],
            '*',
            $where
        );
    }
}