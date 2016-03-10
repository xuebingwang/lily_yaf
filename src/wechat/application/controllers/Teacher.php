<?php
/**
 * Created by PhpStorm.
 * User: xiongxin
 * Date: 16/3/9
 * Time: 下午8:31
 */

/*
 * 完成功能如下:
 * 名师推荐 index
 * 名师详情 detail
 * */
class TeacherController extends Core\Mall {
    public function indexAction() {
        $where = [
          'AND' => [
              'a.status' => TeacherModel::STATUS_OK,
              'a.apply_status' => TeacherModel::APPLY_STATUS_YES
          ]
        ];

        // 按名称查询
        $name = I('name');
        if(!empty($name)){
            $where['AND']['b.name[~]'] = $name ;
        }

        $page = intval(I('page',0));
        $order_by = 'a.like_count DESC';
        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;
        $list = M('t_teacher(a)')->select(
            [
                '[>]t_user(b)' => ['a.user_id' => 'id'],
                '[>]t_user_album(c)' => ['a.user_id' => 'user_id','AND'=>['is_cover'=> 'YES']]
            ],
            [
                'a.*',
                'c.pic_url',
                'b.*'
            ],
            $where
        );
        $this->assign('list',$list);
        $this->layout->title = '名师推荐';
    }

    public function detailAction($id) {
        $where = [
            'AND' => [
                'a.status' => TeacherModel::STATUS_OK,
                'a.apply_status' => TeacherModel::APPLY_STATUS_YES,
                'a.id'=>$id
            ]
        ];

        //个人信息
        $teacher = M('t_teacher(a)')->get(
            [
                '[>]t_user(b)' => ['a.user_id' => 'id'],
            ],
            [
                'b.name',
                'a.*',
            ],
            $where
        );

        if(empty($teacher)) {
            $this->error('该名师不存在');
        }

        //封面
        $album = M('t_teacher(a)')->select(
            [
                '[>]t_user(b)' => ['a.user_id' => 'id'],
                '[>]t_user_album(c)' => ['a.user_id' => 'user_id']
            ],
            [
                'c.*',
            ],
            $where
        );
        $this->assign('album', $album);
        $this->assign('teacher', $teacher);
        $this->layout->title="名师详情";
    }
}