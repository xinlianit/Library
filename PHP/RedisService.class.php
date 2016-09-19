<?php
/**
 * Redis操作类
 */
namespace Lib;

class RedisService {
    /**
     * @desc 自身实例
     * @var instance
     */
    private static $Ins;
    
    /**
     * @desc redis连接资源
     * @var resource
     */
    private static $connect;
    
    public function __construct($host , $port , $auth=null){
        self::$connect = new \Redis();
        self::$connect->connect( $host , $port );
        if(isset($auth)) self::$connect->auth($auth);
    }
    
    /**
     * 设置数据
     * @param string $val           值域
     * @param string $key           键名      
     * @param number $expire        过期时间
     * @return boolean
     */
    public function set($val , $key = null , $expire = 0){
        $key = isset($key) ? $key : md5(time() . mt_rand(1000,9999) . mt_rand(100000,999999) . mt_rand(mt_rand(1000,9999),mt_rand(100000,999999)));
        if(!self::$connect->set($key , $val)) return false;
        if($expire > 0) self::$connect->expire($key , $expire);
        return $key;
    }
    
    /**
     * 获取数据
     * @param string $key       键名
     * @return string|null
     */
    public function get($key){
        return self::$connect->get($key);
    }
    
    /**
     * 当前实例
     * @param unknown $host
     * @param unknown $port
     * @param unknown $auth
     * @return \Lib\instance|\Lib\RedisService
     */
    public static function instance($host , $port , $auth=null){
        if(self::$Ins instanceof self){
            return self::$Ins;
        }else{
            return self::$Ins = new self($host , $port , $auth);
        }
    }
}
