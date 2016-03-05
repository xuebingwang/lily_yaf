<?php
use Core\Mall;
/**
 * @name PlanController
 * @author xuebingwang
 * @desc 商业计划书详情控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class PlanController extends Mall {


    public function commentAction($id){

        if($this->user['is_teacher'] == UserModel::BOOL_YES){
            $this->error('您需要先通过名师认证才能点评！');
        }
        $id = intval($id);

        $item = M('t_business_plan')->get(['id','title'],['id'=>$id]);
        if(empty($item)){
            $this->error('没有找到对应的计划书！');
        }


    }

    /**
     * 计划书详情
     * @param $id
     */
    public function detailAction($id){

        $id = intval($id);
        $item = M('t_business_plan(a)')->get(
            [
                '[>]t_business_plan_count(b)'=>['a.id'=>'plan_id','AND'=>['b.wx_id'=>$this->user['wx_id'],'b.type'=>1]],
                '[><]t_user(c)'=>['a.student_id'=>'id'],
            ],
            ['a.*','b.wx_id','c.name(student_name)','c.company'],
            ['a.id'=>$id]
        );

        if(empty($item)){
            $this->error('没有找到指定的计划书！');
        }

        $this->assign('item',$item);
//        var_dump($item);die;
        $this->_add_count($id,0,$item['view_count']);
        $this->layout->title = '商业计划书详情';
    }

    /**
     * @param $id
     * @param $type
     * @param $count
     * @return bool
     */
    private function _add_count($id,$type,$count){

        $data = [
            'wx_id'         => $this->user['wx_id'],
            'plan_id'       => $id,
            'type'          => $type,
        ];

        if(!empty(M('t_business_plan_count')->get('wx_id',['AND'=>$data]))){
            return false;
        }
        $columns = ['view_count','down_count','like_count'];
        $data['insert_time'] = time_format();
        return M('t_business_plan_count')->insert($data,true) && M('t_business_plan')->update([$columns[$type]=>$count+1],['id'=>$id]);
    }

    /**
     * 点赞计划书
     */
    public function likeAction(){
        $id = intval(I('id',0));
        if(empty($id)){
            $this->error('参数错误，计划书ID不能为空！');
        }
        $item = M('t_business_plan(a)')->get(
            [
                '[>]t_business_plan_count(b)'=>['a.id'=>'plan_id','AND'=>['b.wx_id'=>$this->user['wx_id'],'b.type'=>2]],
            ],
            ['a.like_count','b.wx_id'],
            ['a.id'=>$id]
        );
        if(empty($item)){
            $this->error('没有找到计划书！');
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

    /**
     * 下载计划书附件
     */
    public function downFileAction(){
        if($this->user['is_student'] == UserModel::BOOL_NO && $this->user['is_teacher'] == UserModel::BOOL_NO){
            $this->error('下载计划书需要新人报道或名师认证！');
        }

        $id = intval(I('id',0));
        if(empty($id)){
            $this->error('参数错误，计划书ID不能为空！');
        }
        $item = M('t_business_plan')->get(['file','down_count'],['id'=>$id]);

        if(empty($item)){
            $this->error('没有找到计划书！');
        }

        $this->_add_count($id,1,$item['down_count']);

        $this->success('正在下载',get_qiniu_file_durl($item['file']));
    }

    /**
     * 计划书列表（学生端）
     */
    public function indexAction(){

        $where = ['AND'=>['a.status'=>1]];
        $keyword = I('keyword');
        if(!empty($keyword)){
            $where['AND']['OR'] = [
                'a.title[~]'=>$keyword,
                'a.description[~]'=>$keyword,
            ];
        }
        $cate_id = I('cate_id');
        if(!empty($cate_id)){
            $where['AND']['a.category'] = $cate_id;
        }
        $order = I('order');
        $order_list = [
            'like'=>'like_count DESC',
            'down'=>'down_count DESC',
        ];
        $order_by = 'a.insert_time DESC';
        if(array_key_exists($order,$order_list)){
            $order_by = $order_list[$order];
        }

        $page = intval(I('page',0));

        $where['LIMIT'] = [$page*$this->config->application->pagenum,$this->config->application->pagenum];
        $where['ORDER'] = $order_by;

        $list = M('t_business_plan(a)')->select(
            [
                '[><]t_category(b)'=>['a.category'=>'id'],
                '[><]t_user(c)'=>['a.student_id'=>'id'],
                '[>]t_business_plan_count(d)'=>['a.id'=>'plan_id','AND'=>['d.wx_id'=>$this->user['wx_id'],'d.type'=>1]],
            ],
            [
                'a.*',
                'b.title(category_name)',
                'c.name(student_name)',
                'd.wx_id',
            ],
            $where
        );

        $this->assign('list',$list);
//        echo M()->last_query();
//        die;
        if(IS_AJAX){
            if(empty($list)){
                $this->error('没有更多数据了！');
            }
            $this->success('ok','',['html'=>$this->render('ajax.list')]);
        }

        $cate_list = M('t_category(a)')->select(
            [
                '[><]t_business_plan(b)'=>['a.id'=>'category'],
            ],
            [
                'a.id',
                'a.title',
            ],
            [
                'GROUP'=>'a.id'
            ]
        );
        $this->assign('cate_list',$cate_list);
        $this->layout->title = '商业计划书展示';
    }
}
