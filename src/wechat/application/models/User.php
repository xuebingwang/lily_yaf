<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class UserModel extends Model{

    public $table = 't_user';

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
     * 是
     */
    const BOOL_YES = 'YES';

    /**
     * 不是
     */
    const BOOL_NO = 'NO#';

    /**
     * 名师认证状态:待审核
     */
    const TEACHER_APPLY_STATUS_WAT = 'WAT';

    /**
     * 名师认证状态:审核通过
     */
    const TEACHER_APPLY_STATUS_YES = 'YES';

    /**
     * 名师认证状态:审核拒绝
     */
    const TEACHER_APPLY_STATUS_NO = 'YES';

    /**
     * @param $data
     * @return array|bool
     */
    public function save($data){
        if(isset($data['id'])){
            $user_id = $data['id'];
            unset($data['id']);
            if(!$this->update($data,['id'=>$user_id])){
                return false;
            }
        }else{
            $user_id = $this->insert($data);
        }
        return $user_id;
    }

    /**
     * @param array $data
     * @param null|string $where
     * @return mixed
     */
    public function update($data,$where){

        $data['update_time'] = time_format();
        return parent::update($data,$where);
    }

    /**
     * @param array $data
     * @return array
     */
    public function insert(Array $data){

        $data['insert_time'] = time_format();
        return parent::insert($data);
    }
}
