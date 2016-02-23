<?php
use Yaf\Dispatcher;
/**
 * @name PublicController
 * @author xuebingwang
 * @desc Public控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class CallbackController extends Yaf\Controller_Abstract  {

    protected $wechat;
    protected $raw_data;
    
    public function init(){
//         Dispatcher::getInstance()->returnResponse(true);
        Dispatcher::getInstance()->disableView();
        
        $config_setting = M('t_wechat_setting')->get('*',['id'=>1]);
        $this->wechat = new Wechat($config_setting);
    }

    private function _getWxuserToken(){
        $code = I('code');
        if(empty($code)){
            $this->redirect('/?status=codenull');
            die;
        }

        $user_token = $this->wechat->getOauthAccessToken($code);
        //本地调试用
//        $user_token['openid'] = 'oGg5EwxSCk6_H1kFqSs74Jb8Jkck';
//        $user_token['unionid'] = 'olbkKtxWIOQDYKCQM7GBp7WTNbEU';

        if(empty($user_token) || !isset($user_token['openid'])){
            $this->redirect('/?status=get_user_token_faild');
            die;
        }

        return $user_token;
    }

    public function spreadAction(){
        $this->_wxAutoLogin();
        $forward = urldecode(I('forward',''));
        if($forward){
            $this->redirect($forward);
            die;
        }
    }

    private function _wxAutoLogin(){

        $user_token = $this->_getWxuserToken();

        $model = M('t_wx_user');
        $field = [
            'openid',
            'userid',
            'nickname',
            'headimgurl',
            'unionid',
            'subscribe',
        ];

        $user_info = $model->get($field,['unionid'=>$user_token['unionid']]);

        $cpsid = null;
        //如果用户不存在,并且应用授权作用域是snsapi_userinfo,则请求微信获取用户详情信息
        if(empty($user_info) && $user_token['scope'] == 'snsapi_userinfo'){

            //先通过openid直接获取用户信息
            $user_info = $this->wechat->getUserInfo($user_token['openid']);

            //如果从微信获取用户信息失败,或者用户未关注本公众号
            if(empty($user_info) || $user_info['subscribe'] != 1){
                //通过网页授权获取用户基本信息
                $user_info = $this->wechat->getOauthUserinfo($user_token['access_token'],$user_token['openid']);
                //再拿不到用户信息,则只能退出了
                if(empty($user_info)){
                    $this->redirect('/?status=userinfonull');
                    die;
                }
                //将用户特征信息转为json格式
                $user_info['privilege'] = json_encode($user_info['privilege'],JSON_UNESCAPED_UNICODE);
                $user_info['subscribe'] = -1;
            }

            //开始自动完成用户注册
            $user_info['userid'] = $model->reg($user_info);
            if(!$user_info['userid']){
                $this->redirect('/?status=userregfail');
                die;
            }
        }elseif(empty($user_info) && $user_token['scope'] == 'snsapi_base'){
            //未关注公众号的用户,也执行注册流程,只保存openid和unionid,其他内容置为默认项
            $user_info = [
                            'openid'=>$user_token['openid'],
			                'nickname'=>'',
                            'sex'=>0,
                            'unionid'=>$user_token['unionid'],
                            'subscribe' => -1
                        ];

            //开始完成用户注册
            $user_info['userid'] = $model->reg($user_info);
            if(!$user_info['userid']){
                $this->redirect('/?status=nosub_userregfail');
                die;
            }
            $cpsid = intval(I('state'));
            if($cpsid){
            }else{
                //SeasLog::debug('哇! 这位用户从哪进来的,没有人分享给他网址,竟然自己就进来了!');
            }
        }

        //将用户信息放入会话中,此功能有待完善
        session('user_auth',$user_info);

        return $user_info;
    }

    public function wxMenuAction(){

        $userinfo = $this->_wxAutoLogin();
        $state = I('state');

        //根据state的值跳转到相应的页面
        switch($state){

            case 'myorder':
                $this->redirect('/member/order/index.html');
                break;

            case 'mycard':
                $this->redirect('/member/index/barcode.html');
                break;

            case 'profit':
                $this->redirect('/member/index/shop.html');
                break;

            case 'invite':
                $this->redirect('/member/index/index.html?fx=1');
                break;

            case 'near':
                $this->redirect('/public/map/type/near.html');
                break;

            case 'zhinan':
                $this->redirect('http://mp.weixin.qq.com/s?__biz=MzI3NjAxNzE3OA==&mid=208969664&idx=1&sn=2786b9892004f9471a2a0d7220936152&scene=5#rd');
                break;
            case 'kaidian':
                $this->redirect('/product/detail/goods_id/16.html');
                break;
            case 'haibao':
                $this->redirect('/member/index/setupshop.html');
                break;
            case 'tglink':
                $this->redirect('/member/index/choosedis.html');
                break;

            default:
                $param = '';
                if($userinfo){
                    $param = '&cpsid='.$userinfo['userid'];
                }
                $this->redirect('/?status=success'.$param);
                break;
        }
        die;
    }
}
