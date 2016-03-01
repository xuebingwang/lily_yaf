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
