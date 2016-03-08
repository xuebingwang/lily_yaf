<?php
use Qiniu\Auth;
use Core\Mall;

Yaf\Loader::import(APP_PATH.'helper'.DS.'qiniu.php');
/**
 * @name UploadController
 * @author xuebingwang
 * @desc 上传组件控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class UploadController extends Mall {

    /**
     * 修改头像
     */
    public function editHeadLogoAction(){

        if(empty($this->user['user_id'])){
            $this->error('参数错误，用户ID为空！');
        }

        $this->user['head_logo']= I('source');
        if(empty($this->user['head_logo'])){
            $this->error('头像修改失败，地址为空！');
        }
        if(M('t_user')->update(['headimgurl'=>$this->user['head_logo']],['id'=>$this->user['user_id']])){

            session('user_auth',$this->user);
            $this->success('修改成功！',imageView2($this->user['head_logo'],100,100));
        }else{
            $this->error('头像保存失败，请重新再试或联系客服人员！');
        }
    }

    public function downFileTokenAction(){
        // 对链接进行签名
        $data = ['source'=>get_qiniu_file_durl(I('post.source'))];

        $this->ajaxReturn($data);
    }


    public function downTokenAction(){
        // 构建Auth对象
        $auth = new Auth($this->config->qiniu->accessKey, $this->config->qiniu->secrectKey);

        // 私有空间中的外链 http://<domain>/<file_key>
        // 对链接进行签名
        $data = [];
        if(!empty(I('post.url'))){
            $data['url'] = $auth->privateDownloadUrl(I('post.url'));
        }
        if(!empty(I('post.source'))) {
            $data['source'] = $auth->privateDownloadUrl(I('post.source'));
        }

        $this->ajaxReturn($data);
    }

    public function fileTokenAction(){
        $auth = new Auth($this->config->qiniu->accessKey, $this->config->qiniu->secrectKey);
        $upToken = $auth->uploadToken($this->config->qiniu->file->bucket);
        $this->ajaxReturn(['uptoken'=>$upToken]);
    }

    public function picTokenAction(){

        $auth = new Auth($this->config->qiniu->accessKey, $this->config->qiniu->secrectKey);
        $upToken = $auth->uploadToken($this->config->qiniu->picture->bucket);
        $this->ajaxReturn(['uptoken'=>$upToken]);
    }
}
