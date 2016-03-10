<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class TeacherModel extends UserModel{

    public $table = 't_teacher';

    /**
     * 名师认证状态:待审核
     */
    const APPLY_STATUS_WAT = 'WAT';

    /**
     * 名师认证状态:审核通过
     */
    const APPLY_STATUS_YES = 'YES';

    /**
     * 名师认证状态:审核拒绝
     */
    const APPLY_STATUS_NO = 'NO#';

    /**
     * @param $data
     * @return bool
     */
    public function reg(Array $data){
        $model      = new UserModel();
        $teacher    = [
            'good_at'=>$data['good_at'],
            'description'=>$data['description'],
            'apply_status'=>self::APPLY_STATUS_WAT
        ];
        unset($data['good_at']);
        unset($data['description']);

        $this->getPDO()->beginTransaction();//开启事务处理

        $user_id    = $model->save($data);

        $id         = $this->get('id',['user_id'=>$user_id]);

        if(empty($id)){
            $teacher['user_id'] = $user_id;
            $flag   = $this->insert($teacher);
        }else{
            $flag   = $this->setId($id)->update($teacher);
        }

        if($user_id && $flag){
            $this->getPDO()->commit();
        }else{
            $this->getPDO()->rollback();
        }

        return $user_id && $flag;
    }

    public function getItem(){

        return $this->get('*',
            [
                'AND'=>[
                        'id'=>$this->id,
                        'status'=>self::STATUS_OK,
                        'apply_status[!]'=>self::APPLY_STATUS_NO
                    ]
            ]
        );
    }
}
