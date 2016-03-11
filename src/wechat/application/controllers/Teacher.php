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

        $courseName = I('name');
        if (!empty($courseName)) {
            $where['AND']['a.title[~]'] = $courseName;
        }

        $page = intval(I('page',0));
        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $model = new CourseModel();
        $list = $model->getList($where);
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


    /*
     * 验证用户信息
     * */
    private function _check_user(){

        if(empty($this->user['user_id'])){
            $this->error('您还没有做过认证或报道！');
        }

        if(!empty($this->user['teacher_id'])){

            if($this->user['apply_status'] == TeacherModel::APPLY_STATUS_WAT){
                $this->error('您的资料正在审核中，审核通过后才能点评！');
            }

            if($this->user['apply_status'] != TeacherModel::APPLY_STATUS_YES){
                $this->error('您需要先通过名师认证才能点评！',U('/public/regTeacher'),['btn_text'=>'去认证']);
            }

            if($this->user['teacher_status'] != TeacherModel::STATUS_OK){
                $this->error('您的名师状态不正常！');
            }
        }

        if(!empty($this->user['student_id']) && $this->user['student_status'] != StudentModel::STATUS_OK){
            $this->error('您的学生状态不正常！');
        }
    }

    /*
     * params:
     * $id 课件id
     * $type 0:查看,1:下载,2:点赞
     * $count 操作次数
     *
     * return: bool
     * */
    private function _add_count($id,$type,$count){

        $data = [
            'wx_id'         => $this->user['wx_id'],
            'course_id'       => $id,
            'type'          => $type,
        ];

        //如果已经执行过该操作直接返回
        if(!empty(M('t_teacher_course_count')->get('wx_id',['AND'=>$data]))){
            return false;
        }
        //            0:查看,1:下载,2:点赞
        $columns = ['view_count','down_count','like_count'];
        $data['insert_time'] = time_format();
        return M('t_teacher_course_count')->insert($data,true) && M('t_teacher_course')->update([$columns[$type]=>$count+1],['id'=>$id]);
    }

    /**
     * 下载课件附件
     */
    public function downCourseAction(){

        $this->_check_user();

        $id = intval(I('id',0));
        if(empty($id)){
            $this->error('参数错误，课件ID不能为空！');
        }
        $item = M('t_teacher_course')->get(['file','down_count'],['AND'=>['id'=>$id,'status'=>CourseModel::STATUS_OK]]);

        if(empty($item)){
            $this->error('没有找到课件！');
        }

        $this->_add_count($id,1,$item['down_count']);

        $this->success('正在下载',get_qiniu_file_durl($item['file']));
    }

    /**
     * 课件点赞
     */
    public function likeAction(){
        $id = intval(I('id',0));
        if(empty($id)){
            $this->error('参数错误，课件ID不能为空！');
        }

        $item = M('t_teacher_course(a)')->get(
            [
                '[>]t_teacher_course_count(b)'=>['a.id'=>'course_id','AND'=>['b.wx_id'=>$this->user['wx_id'],'b.type'=>2]],
            ],
            ['a.like_count','b.wx_id'],
            ['AND'=>['a.id'=>$id,'a.status'=>CourseModel::STATUS_OK]]
        );

        if(empty($item)){
            $this->error('没有找到课件！');
        }

        if(!empty($item['wx_id'])){
            $this->error('您已经点过赞了，不能重复点赞！');
        }

        if($this->_add_count($id,2,$item['like_count'])){

            $this->success('点赞成功','',['count'=>$item['like_count']+1]);
        }else{
            $this->error('点赞失败，请重新再试！');
        }
    }

    public function detailCourseAction($id) {
        $where = [
            'AND' => ['a.id'=>$id]
        ];
        $model = new CourseModel();
        $course = $model->getCourse($where);

        if (empty($course)) {
            $this->error('没有找到课件');
        }
        $this->_add_count($id,0,$course['view_count']);
        $this->assign('item', $course);
    }
}