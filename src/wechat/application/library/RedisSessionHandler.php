<?php
class RedisSessionHandler implements SessionHandlerInterface{

    private $redis;
    private $config;
    private $namespace;
    private $session_id = null;
    private $maxlifetime = null;

    public function __construct($config) {
        //初始化redis
        $this->config = array(
            'namespace'=>'',
            'host'=>'127.0.0.1',
            'port'=>'6789',
            'auth'=>'',
            'db'=>0,
            'session_name'=>'PHPSESSID',
        );
        $this->config = array_merge($this->config,$config);
        $this->redis = new Redis();
        $this->redis->connect($this->config['host'],$this->config['port']);
        if(!empty($this->config['auth'])){
            $ret = $this->redis->auth($this->config['auth']);
            if(!$ret){
                return false;
            }
            $this->redis->select($this->config['db']);
        }
        //设置当前的sessionid生成的命名空间
        $this->namespace = $this->config['namespace'];
        if(!empty($this->namespace)){
            $this->namespace .=':';
        }
        //设置初始化的session_id
        if(!empty($this->config['session_name'])){
            //没有设置过session，新的会话，需要重新生成sessionid
            if ( empty($_COOKIE[$this->config['session_name']]) ) {
                $this->session_id = true;
            }
        }
    }

    public function open($savePath, $sessionName){
        $connect = $this->redis->connect($this->config['host'],$this->config['port']);
        if(!$connect) return false;
        if(!empty($this->config['auth'])){
            $ret = $this->redis->auth($this->config['auth']);
            if(!$ret){
                return false;
            }
            $this->redis->select($this->config['db']);
        }
        //在初次设置的时候，重设PHP本身的session_id并判断session_id是否已经存在
        $id = session_id();
        if($this->session_id && $this->session_id != $id){
            do{
                $this->session_id = $this->session_id();
            }while($this->redis->exists($this->namespace.$this->session_id));
            session_id($this->session_id);
        }
        //设置生成周期
        $this->maxlifetime = ini_get("session.gc_maxlifetime");
        return true;
    }
    public function close(){
        $this->redis->close();
        return true;
    }
    public function read($id){
        $data = json_decode($this->redis->get($this->namespace.$id),TRUE);
        return serialize($data);
    }
    public function write($id, $data){
        $json = json_encode(unserialize($data),JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
        return $this->redis->setex($this->namespace.$id,$this->maxlifetime,$json);
    }
    public function destroy($id){
        $this->redis->delete($this->namespace.$id);
        return true;
    }
    public function gc($maxlifetime){
        return true;
    }

    /**
     * 生成guid
     */
    private function session_id(){
        $uid = uniqid("", true);
        $data = $this->namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['SERVER_ADDR'];
        $data .= $_SERVER['SERVER_PORT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . md5($data)));
        return substr($hash,0,32);
    }
}

//$param =['host'=>'112.124.6.88','auth'=>'paas2009'];
//$handler = new RedisSessionHandler($param);
//session_set_save_handler($handler, true);
//session_start();
/*
if(empty($_SESSION)){
				$_SESSION['test']= ['a'=>1,'b'=>2];
}
var_dump($_SESSION);
*/
