<?php
/**
 * Created by PhpStorm.
 * User: xuebin<406964108@qq.com>
 * Date: 2014/12/24
 * Time: 17:13
 * @copyright Copyright (c) 2014
 */

class WxUserModel extends Model{

    public $table = 't_wx_user';

    public function save(Array $data){

        $where = ['unionid'=>$data['unionid']];
	    $uid = $this->get('wx_id',$where);

        //如果找不到用户数据, 执行注册流程
        if(empty($uid)){
            //插入至微信用户表
            return $this->reg($data);
        }

        //这两项不允许修改, 也不存在修改的可能
        unset($data['openid']);
        unset($data['unionid']);

        $data['update_time'] = time_format();
        return $this->update($data,['wx_id'=>$uid]);
    }

    public function reg(Array $data){

        if(empty($data['headimgurl'])){
            $data['headimgurl'] = DOMAIN.'/misc/images/avator.jpg';
        }
        $data['insert_time'] = time_format();
        return $this->insert($data);
    }

    public function getUser($openid){
        $field = [
            'openid',
            $this->table.'.wx_id',
            'nickname',
            't_wx_user.headimgurl',
            'unionid',
            'subscribe',
            'b.id(user_id)',
            'b.name',
            'b.headimgurl(head_logo)',
            'b.mobile',
            'c.id(student_id)',
            'c.status(student_status)',
            'd.id(teacher_id)',
            'd.status(teacher_status)',
            'd.apply_status',
        ];
        return $this->get(
            [
                '[>]t_user(b)'=>['wx_id'=>'wx_id','AND'=>['b.status'=>UserModel::STATUS_OK]],
                '[>]t_student(c)'=>['b.id'=>'user_id'],
                '[>]t_teacher(d)'=>['b.id'=>'user_id'],
            ],
            $field,
            ['AND'=>['openid'=>$openid]]
        );
    }
}
