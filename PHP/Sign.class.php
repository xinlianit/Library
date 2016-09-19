<?php 
/**
 * 数据签名
 */
namespace Lib;

class Sign {
    
    /**
     * @desc 实例
     * @var instance
     */
    private static $ins;
    
    /**
     * @desc 签名密钥
     * @var string
     */
    private $secret_key = null;
    
    /**
     * @desc 加密计算盐值
     * @var string
     */
    public static $slat = null;
    
    /**
     * @desc 秘钥键名
     * @var string
     */
    private $secret_key_name = 'key';
    
    /**
     * @desc 签名键名
     * @var string
     */
    private $sign_key_name = 'sign';
    
    /**
     * @desc 编译签名函数;md5|crypt|sha1|base64_encode
     * @var string
     */
    public static $make_sign_func = 'md5';
    
    public function __construct($secret_key , $sign_key_name , $secret_key_name){
        if( isset( $secret_key ) ) 
            $this->secret_key               = $secret_key;
        if( isset($sign_key_name) )
            $this->sign_key_name            = $sign_key_name;
        if( isset($secret_key_name) )
            $this->secret_key_name          = $secret_key_name;
    }
    
    /**
     * 创建实例
     */
    public static function instance($secret_key=null , $sign_key_name=null , $secret_key_name=null){
        if(self::$ins instanceof self) 
            return self::$ins;
        else
            return self::$ins = new self( $secret_key , $sign_key_name , $secret_key_name );
    }
    
    /**
     * 获取签名字符串
     * @param array $data       签名数据
     * @return NULL|string
     */
    public function getSignStr($data=array()){
        if( empty($data) ) 
            return null;
        //数组键名升序排序
        ksort( $data );
        $sign_str = '';
        foreach( $data as $k => $v ){
            //过滤签名、空值、数组
            if( $k != $this->sign_key_name && $v != '' && !is_array($v) )
                $sign_str .= $k . '=' . html_entity_decode( $v ) . '&';
        }
        
        //拼接密钥
        $sign_str .= $this->secret_key_name . '=' . $this->secret_key;
        
        return $sign_str;
    }
    
    /**
     * 编译生成签名串
     * @param string $sign_str          签名字符串
     * @param string $make_func         签名编译函数
     * @param string $slat              加密盐值
     * @return NULL|boolean|string
     */
    public function makeSign($sign_str , $make_func=null , $slat=null){
        if( !isset($sign_str) ) 
            return null;
        
        $make_func = isset( $make_func ) ? $make_func : self::$make_sign_func;
        $slat      = isset( $slat ) ? $slat : self::$slat;
        
        //编译函数检测
        if( !function_exists( $make_func ) )
            return false;
        
        switch( $make_func ){
            //基于标准 UNIX DES 算法或系统上其他可用的替代算法的散列字符串
            case 'crypt':
                $make_result = call_user_func( $make_func , $sign_str , $slat );
                break;
            default:
                $make_result = call_user_func( $make_func , $sign_str );
                break;
        }
        
        return strtoupper( $make_result );
    }
    
    /**
     * 验证签名
     * @param string $sign              签名值
     * @param array $data               签名数据
     * @param string $make_func         签名编译函数
     * @return NULL|boolean     
     */
    public function verifySign($sign , $data=array() , $make_func=null){
        if( !isset($sign) || empty($data) )
            return null;
        
        //生成签名字符串
        $sign_str = self::getSignStr( $data );
        
        //编译生成签名
        $sign_val = self::makeSign($sign_str , $make_func); 
        
        if( $sign != $sign_val ) 
            return false;
        
        return true;
    }
}
?>