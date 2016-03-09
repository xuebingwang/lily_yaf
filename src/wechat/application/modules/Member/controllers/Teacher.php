<?php
use Core\Mall;
/**
 * @name TeacherController
 * @author xuebingwang
 * @desc 老师控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class TeacherController extends Mall {

    public function init(){
        parent::init();
        if($this->user['is_teacher'] != UserModel::BOOL_YES){
            $this->error('您需要先通过名师认证才能点评！',U('/public/regTeacher'),['btn_text'=>'去认证']);
        }

        $this->assign('user',$this->user);
    }

    public function albumAction(){
        $model = new UserAlbumModel();
        $model->user_id = $this->user['user_id'];

        if(IS_POST){

            $pic_url = I('pic_url',[]);
            if(empty($pic_url)){
                $this->error('请至少上传一张照片！');
            }

            $is_cover = I('is_cover');
            $is_cover = empty($is_cover) ? $pic_url[0] : $is_cover;
            $datas = [];

            $flag = false;
            foreach($pic_url as $key=>$pic){
                $tmp = [
                    'user_id'=>$this->user['user_id'],
                    'pic_url'=>$pic,
                    'sort'=>$key,
                    'is_cover'=>($pic == $is_cover ? UserAlbumModel::BOOL_YES : UserAlbumModel::BOOL_NO),
                    'insert_time'=>time_format(),
                ];
                if($tmp['is_cover'] == UserAlbumModel::BOOL_YES){
                    $flag = true;
                }
                $datas[] = $tmp;
            }

            if(!$flag){
                $datas[0]['is_cover'] = UserAlbumModel::BOOL_YES;
            }
            if($model->save($datas)){
                $this->success('保存成功！',U('/member/teacher/index'));
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }

        $list = [];
        foreach($model->getList() as $item){
            $list[] = [
                'source'=>$item['pic_url'],
                'url'=>imageView2($item['pic_url'],30,30),
                'is_cover'=>$item['is_cover'] == UserAlbumModel::BOOL_YES ? true : false,
            ];
        }
        $this->assign('list',$list);
        $this->layout->title = '我的相册';

    }

    public function editAction(){
        if(IS_POST){
            $data = [];
            $data['name'] = I('name');
            if(empty($data['name'])){
                $this->error('请输入姓名！');
            }
            if(length_regex($data['name'],20)){
                $this->error('姓名最大允许输入20个字符！');
            }

            $data['mobile'] = I('mobile');
            if(empty($data['mobile'])){
                $this->error('请输入手机号码！');
            }
            if(regex($data['mobile'],'mobile')){
                $this->error('请输入正确的手机号码！');
            }

            $data['good_at'] = I('good_at');
            if(empty($data['good_at'])){
                $this->error('请输入擅长内容！');
            }
            if(length_regex($data['good_at'],50)){
                $this->error('擅长最大允许输入50个字符！');
            }

            $data['qr_code_url'] = I('qr_code_url');
            if(empty($data['qr_code_url'])){
                $this->error('请上传二维码！');
            }


            $data['description'] = I('description');
            if(empty($data['description'])){
                $this->error('请输入个人简介！');
            }
            if(length_regex($data['good_at'],500)){
                $this->error('个人简介最大允许输入50个字符！');
            }

            $data['update_time'] = time_format();

            if(M('t_user')->update($data,['id'=>$this->user['user_id']])){
                $this->success('保存成功！');
            }else{
                $this->error('保存失败，请重新再试或联系客服人员！');
            }
        }
        $item = M('t_user')->get('*',['id'=>$this->user['user_id']]);
        $this->assign('item',$item);

        $this->layout->title = '我的资料';
    }

    /**
     *
     */
    public function indexAction(){
        $this->layout->title = '我的';
        $this->assign('is_teacher',true);
        $this->getResponse()->setBody($this->render('../info/index'));
        return false;
    }
}
