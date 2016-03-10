<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class StudentModel extends UserModel{

    public $table = 't_student';

    /**
     * @param $data
     * @return bool
     */
    public function reg(Array $data){
        $model      = new UserModel();
        $student    = ['company'=>$data['company']];
        unset($data['company']);

        $this->getPDO()->beginTransaction();//开启事务处理
        $student['user_id'] = $model->save($data);

        $flag               = $this->insert($student);

        if($student['user_id'] && $flag){
            $this->getPDO()->commit();
        }else{
            $this->getPDO()->rollback();
        }

        return $student['user_id'] && $flag;
    }
}
