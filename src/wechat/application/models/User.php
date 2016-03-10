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
     * @var主键ID
     */
    protected $id;

    /**
     * @param $id
     * @return $this
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * @param $data
     * @return array|bool
     */
    public function save($data){

        if(isset($data['id'])){
            $user_id = $data['id'];
            unset($data['id']);

            if(!$this->setId($user_id)->update($data)){
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
    public function update($data,$where=null){
        if(empty($where)){
            $where= ['id'=>$this->id];
        }

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

    /**
     * @return bool
     */
    public function getItem(){

        return $this->get('*',
            [
                'AND'=>[
                    'id'=>$this->id,
                    'status'=>self::STATUS_OK,
                ]
            ]
        );
    }
}
