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
 * 名师课件 course
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

        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',[
                    'html'=>$this->render('ajax.list'),
                    'list_total'=>count($list),
                    'page'=>$page+1
                ]
            );
        }
        $this->layout->title = '名师推荐';
        unset($where['LIMIT']);
        $count = M('t_teacher(a)')->count(
            [
                '[>]t_user(b)' => ['a.user_id' => 'id'],
                '[>]t_user_album(c)' => ['a.user_id' => 'user_id','AND'=>['is_cover'=> 'YES']]
            ],
            '*',
            $where
        );
        $this->getView()->assign('total',intval($count));
        $this->getView()->assign('page',$page+1);
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

    public function courseAction() {

        $where = [
            'AND'=>[
                'a.status'=>CourseModel::STATUS_OK
            ]
        ];
        $order_by = 'a.insert_time DESC';

        $page = intval(I('page',0));
        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $model = new CourseModel();
        $list = $model->getList($where);
        var_dump($list);
        $this->assign('list',$list);
//        echo M()->last_query();
//        die;
        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',[
                    'html'=>$this->render('ajax.list'),
                    'list_total'=>count($list),
                    'page'=>$page+1
                ]
            );
        }

        $this->layout->title = '名师课件';
        $this->getView()->assign('total',intval($model->getListCount($where)));
        $this->getView()->assign('page',$page+1);
    }

    public function downCourseAction() {
        
    }
}