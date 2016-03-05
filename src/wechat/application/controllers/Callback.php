<?php
/**
 * @name PublicController
 * @author xuebingwang
 * @desc Public控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
*/
class CallbackController extends Core\Wechat  {

    /**
     * 微信接口Action
     * @return bool
     */
    public function wechatAction(){

        $this->raw_data = file_get_contents('php://input');

        SeasLog::debug("请求内容==>".$this->raw_data);

        $this->wechat->setPostXml($this->raw_data)->getRev();

        if(!$this->wechat->valid()){
            die;
            //return false;
        }

        $type = $this->wechat->getRevType();
        switch($type) {
            case Wechat::MSGTYPE_EVENT:
                $this->_do_event();
                break;

            case Wechat::MSGTYPE_IMAGE:
            case Wechat::MSGTYPE_TEXT:
            case Wechat::MSGTYPE_VOICE:
            case Wechat::MSGTYPE_VIDEO:
            case Wechat::MSGTYPE_LINK:
            case Wechat::MSGTYPE_SHORTVIDEO:
                $this->wechat->transfer_customer_service()->reply();

                break;

            default:
                $this->wechat->text("help info")->reply();
        }
        die;
    }

    /**
     * 事件方法
     */
    private function _do_event(){

        $event = $this->wechat->getRevEvent();
        switch($event['event']){
            case Wechat::EVENT_MENU_CLICK:
                if(method_exists($this,$event['key'])){
                    $this->$event['key']();
                }else{
                    $this->wechat->text('功能完善中，感谢您的关注！')->reply();
                }
                break;
            case Wechat::EVENT_LOCATION:
                //地理位置
                break;
            case Wechat::EVENT_SUBSCRIBE:
                //关注

                $openId = $this->wechat->getRevFrom();
                //首先获取微信用户的详细信息
                $userinfo = $this->wechat->getUserInfo($openId);

                if(empty($userinfo)){
                    SeasLog::debug('获取用户失败!');
                    return false;
                }
                $this->_subscribe($userinfo);

                $this->wechat->news([
                    [
                        'Title'=>'尊敬的'.$userinfo['nickname'].'，欢迎您关注百合花，点击进入首页',
                        'Description'=>'',
                        'PicUrl'=>$userinfo['headimgurl'],
                        'Url'=>DOMAIN,
                    ],
                ])->reply();

                break;
            case Wechat::EVENT_UNSUBSCRIBE:

                $openId = $this->wechat->getRevFrom();
                $model = new Model('t_wx_user');
                $model->update(['subscribe'=>0],['openid'=>$openId]);

                break;
        }

    }

    private function _subscribe($userinfo){

        $model = new WxUserModel();
        //用户注册
        if($model->save($userinfo)){

            SeasLog::debug('微信关注用户信息保存成功了!');
        }else{
            SeasLog::debug(M()->last_query());
            SeasLog::error('出事了,关注完之后竟然在最后一步出错了,没保存成功!');
        }
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

        $model = new WxUserModel();
        $user_info = $model->getUser($user_token['openid']);

        SeasLog::debug($model->last_query());
        SeasLog::debug(var_export($user_info,true));
        //如果用户不存在,并且应用授权作用域是snsapi_userinfo,则请求微信获取用户详情信息
        //非静默授权
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
            $user_info['wx_id'] = $model->reg($user_info);
            if(!$user_info['wx_id']){
                $this->redirect('/?status=userregfail');
                die;
            }
        }elseif(empty($user_info) && $user_token['scope'] == 'snsapi_base'){
            //静默授权
            //未关注公众号的用户,也执行注册流程,只保存openid和unionid,其他内容置为默认项
            $user_info = [
                            'openid'=>$user_token['openid'],
			                'nickname'=>'',
                            'sex'=>0,
                            'unionid'=>isset($user_token['unionid']) ? $user_token['unionid'] : '',
                            'subscribe' => -1
                        ];

            //开始完成用户注册
            $user_info['wx_id'] = $model->reg($user_info);
            if(!$user_info['wx_id']){
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
                    $param = '&cpsid='.$userinfo['wx_id'];
                }
                $this->redirect('/?status=success'.$param);
                break;
        }
        die;
    }
}
