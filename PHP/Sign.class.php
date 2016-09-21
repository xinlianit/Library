<?php 
/*********************************************************************************************************
 *                                                                                                       *
 * 数据签名                                                                                                                                                                                                                                                                                                  *
 *    一、md5|crypt|sha1|base64_encode|rsa 签名规则：                                                                                                                                                                          *
 *    1、数组按键名正序排序；如：array( 'c'=>1 , 'b'=>2 , 'a'=>9 ) 排序后  array( 'a'=>9 , 'b'=>2 , 'c'=>1 )         *
 *    2、遍历数组，排除键名为sign、值为空、值为数组的键值，连接成字符串； 如：$str = "a=9&b=2&c=1"                              *
 *    3、连接秘钥key；如：$str .= "&key=14596386c530596d93ac260985e35edf"                                     *
 *    4、（md5|crypt|sha1|base64_encode|rsa）加密字符串 ；如：md5($str)                                          *
 *    5、得到签名后，转大写输出                                                                                                                                                                                                                                                      *
 *                                                                                                       *
 *    二、rsa 签名规则
 *    1、数组按键名正序排序；如：array( 'c'=>1 , 'b'=>2 , 'a'=>9 ) 排序后  array( 'a'=>9 , 'b'=>2 , 'c'=>1 )         *
 *    2、遍历数组，排除键名为sign、值为空、值为数组的键值，连接成字符串； 如：$str = "a=9&b=2&c=1"                              *
 *    3、使用RSA公钥签名 rsa_public_key.pem                                                                    *
 *    4、得到RSA签名值                                                                                                                                                                                                                                                                      *
 *                                                                                                        *
 *********************************************************************************************************/
namespace Library\PHP;

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
    
    /**
     * RSA公钥
     * @var string
     */
    public static $rsa_public_key = null;
    
    /**
     * RSA私钥
     * @var string
     */
    public static $rsa_private_key = null;
    
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
     * RSA签名
     * @param string $data                       签名数据
     * @return string|boolean
     */
    public function rsaSign($data){
        $key_id = openssl_get_privatekey( self::$rsa_private_key );
        if( !$key_id )
            return false;
    
        //数据签名
        $sign_rs = openssl_sign( $data , $signature , $key_id );
        if( !$sign_rs )
            return false;

        //释放秘钥
        openssl_free_key( $key_id );
        //base64二次加密
        $signature = base64_encode( $signature );

        return $signature;
    }
    
    /**
     * RSA验签
     * @param string $data          验签数据
     * @param string $sign          签名值
     * @return boolean
     */
    public function rsaVerify($data , $sign){
        //base64解密
        $sign = base64_decode( $sign );
        
        //是否设置RSA公钥
        if( !isset(self::$rsa_public_key) )
            return false;
        
        //获取公钥id
        $key_id = openssl_get_publickey( self::$rsa_public_key );
        
        if( !$key_id )
            return false;
        
        //验证签名
        $verify_rs = openssl_verify( $data , $sign , $key_id );
        
        //释放秘钥
        openssl_free_key( $key_id );
        
        if( $verify_rs == 1 ) 
            return true;
        else
            return false;
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
        if( strtoupper(self::$make_sign_func) === 'RSA' ){
            $sign_str = rtrim( $sign_str , '&' );
        }else{
            $sign_str .= $this->secret_key_name . '=' . $this->secret_key;
        }
        
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
        if( !function_exists( $make_func ) && strtoupper($make_func) != 'RSA' )
            return false;
        
        switch( strtoupper($make_func) ){
            //基于标准 UNIX DES 算法或系统上其他可用的替代算法的散列字符串
            case 'CRYPT':
                $make_result = strtoupper(call_user_func( $make_func , $sign_str , $slat ));
                break;
            //RSA签名
            case 'RSA':
                $make_result = $this->rsaSign( $sign_str );
                break;
            default:
                $make_result = strtoupper(call_user_func( $make_func , $sign_str ));
                break;
        }
        
        return $make_result;
    }
    
    /**
     * 验证签名
     * @param string $sign              签名值
     * @param array $data               签名数据
     * @param string $make_func         签名编译函数
     * @param string $slat              加密盐值
     * @return NULL|boolean     
     */
    public function verifySign($sign , $data=array() , $make_func=null , $slat=null){
        if( !isset($sign) || empty($data) )
            return null;
        
        //生成签名字符串
        $sign_str = self::getSignStr( $data );
        
        if( strtoupper(self::$make_sign_func) === 'RSA' ){
            //RSA验签
            if( !$this->rsaVerify( $sign_str , $sign ) )
                return false;
        }else{
            //其他验签
            $sign_val = self::makeSign( $sign_str , $make_func , $slat );
            
            if( $sign != $sign_val )
                return false;
        }
        
        return true;
    }
}
?>