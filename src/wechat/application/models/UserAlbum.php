<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class UserAlbumModel extends Model{

    public $table = 't_user_album';

    /**
     * 是
     */
    const BOOL_YES = 'YES';

    /**
     * 不是
     */
    const BOOL_NO = 'NO#';

    public $user_id;

    public function save($data){

        $this->delete(['user_id'=>$this->user_id]);

        return $this->insert($data);

//        $this->getPDO()->beginTransaction();//开启事务处理
//
//        $this->getPDO()->commit();
//
//        $this->getPDO()->rollback();
    }

    public function getList(){

        return $this->select('*',['user_id'=>$this->user_id]);
    }
}
