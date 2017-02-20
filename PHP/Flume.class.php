<?php
/**
 * Flume-ng SDK for PHP
 * @author JiRY<390066398@qq.com>
 */
class Flume{
    /**
     * 对象实例
     * @var type object
     */
    private static $Ins = null;
    
    /**
     * 协议
     * @var type string
     */
    public $protocol = 'tcp';
    
    /**
     * 主机地址
     * @var type string
     */
    public $host;
    
    /**
     * 主机端口
     * @var type int
     */
    public $port = 8888;
    
    /**
     * 连接超时：秒
     * @var type int
     */
    public $timeout = 30;
    
    /**
     * socket 句柄
     * @var type resource
     */
    private static $client = null;
    
    /**
     * 初始化
     * @param string $host      主机地址
     * @param int $port         主机端口
     * @param string protocol   协议
     * @param int $timeout      连接超时
     * @param string $flag      连接标志；STREAM_CLIENT_CONNECT：默认连接、STREAM_CLIENT_ASYNC_CONNECT：异步连接、STREAM_CLIENT_PERSISTENT：长连接
     */
    public function __construct($host, $port=null, $protocol=null, $timeout=null ,$flag=STREAM_CLIENT_CONNECT){
        if(!$host){
            throw new Exception("No target host requested!"); 
        }
        
        $this->host = $host;
        
        if($port !== null || $port !== ''){
            $this->port = $port;
        }
        
        if($protocol !== null || $protocol !== ''){
            $this->protocol = $protocol;
        }
        
        if($timeout !== null || $timeout !== ''){
            $this->timeout = $timeout;
        }
        
        //建立一个socket流客户端连接
        self::$client = stream_socket_client($this->protocol . '://' . $this->host . ':' . $this->port, $err_code, $err_msg, $this->timeout, $flag);
        
        if( !self::$client ){
            throw new Exception("Connect Fail:Error Code ".$err_code.",Error Message ".$err_msg);
        }
    }
    
    public function __destruct(){
        self::close();
    }
    
    /**
     * 获取实例
     * @param string $host      主机地址
     * @param int $port         主机端口
     * @param string protocol   协议
     * @param int $timeout      连接超时
     */
    public static function getIns($host, $port=null, $protocol=null, $timeout=null){
        if(self::$Ins instanceof self){
            return self::$Ins;
        }else{
            return self::$Ins = new self($host, $port, $protocol, $timeout);
        }
    }

    /**
     * 发送数据
     * @param string $data         发送数据
     * @return boolean
     */
    public static function push($data){
        if(fwrite(self::$client, $data . "\r\n")){
            return true;
        }
        return false;
    }
    
    /**
     * 关闭连接
     */
    public static function close(){
        if(self::$client){
            fclose(self::$client);
        }
    }
    
}