<?php
/**
 * Created by PhpStorm.
 * User: xiongxin
 * Date: 16/3/10
 * Time: 下午5:56
 * 老师端:
 * 我的课件
 * 添加课件
 */

class CourseController extends Core\Mall {
    /**
     * 我的课件列表
     */
    public function indexAction(){
        $where = [
            'AND'=>[
                'a.status'=>CourseModel::STATUS_OK,
                'a.user_id'=>$this->user['user_id']
            ]
        ];
        $order_by = 'a.insert_time DESC';

        $page = intval(I('page',0));
        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $model = new CourseModel();
        $list = $model->getList2($where);

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

        $this->layout->title = '我的课件';
        $this->getView()->assign('total',intval($model->getListCount($where)));
        $this->getView()->assign('page',$page+1);
    }


    public function addAction() {
        if(IS_POST){
            $data = [];
            $data['title'] = I('title');
            if(empty($data['title'])){
                $this->error('请填写课件标题！');
            }
            if(length_regex($data['title'],60)){
                $this->error('课件标题最大允许输入60个字符！');
            }
            $data['category'] = I('category');
            if(empty($data['category'])){
                $this->error('请选择课件分类！');
            }
            $data['logo'] = I('logo');
            if(empty($data['logo'])){
                $this->error('请上传课件封面！');
            }
            $data['file'] = I('plan_file');
            if(empty($data['file'])){
                $this->error('请上传课件附件！');
            }
            $data['description'] = I('description');
            if(empty($data['description'])){
                $this->error('请上传课件附件！');
            }
            if(length_regex($data['description'],5000)){
                $this->error('课件详细描述最大允许输入5000个字符！');
            }

            $data['user_id'] = $this->user['user_id'];
            $data['insert_time'] = time_format();

            if(M('t_teacher_course')->insert($data)){
                $this->success('保存成功！',U('/'));
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }

        $cate_list = [];
        foreach(M('t_category')->select(['id','pid','title'],['status'=>1]) as $item){
            $cate_list[$item['pid']][$item['id']] = $item['title'];
        }
        $this->assign('cate_list',$cate_list);
        $this->layout->title = '上传课件';
    }
}