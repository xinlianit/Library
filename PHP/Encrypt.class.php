<?php
/**
 * 数据加密
 */
namespace Library\PHP;

class Encrypt {
    /**
     * 实例对象
     * @var instance
     */
    private static $ins;
    
    /**
     * 加密类型;AES|RSA
     * @var string
     */
    private $type = 'AES';
    
    /**
     * 加密字节
     * @var integer
     */
    private $_bit = MCRYPT_RIJNDAEL_256;
    
    /**
     * AES加密模式
     * @var string
     */
    private $_aes_mode = MCRYPT_MODE_ECB;
    
    /**
     * RSA加密模式
     * @var string
     */
    private $_rsa_mode = 'PRIVATE';
    
    /**
     * base64二次加密
     * @var boolean
     */
    private $_use_base64 = true;
    
    /**
     * 初始向量大小
     * @var int
     */
    private $_iv_size = null;
    
    /**
     * 初始向量
     * @var string
     */
    private $_iv = null;
    
    /**
     * RSA私钥
     * @var string
     */
    private $_rsa_private_key = null;
    
    /**
     * RSA公钥
     * @var string
     */
    private $_rsa_public_key = null;
    
    /**
     * 加密秘钥
     * @var string
     */
    public static $secret_key = null;
    
    public function __construct($secret_key , $type){
        if( isset($secret_key) )
            self::$secret_key = $secret_key;
        if( isset($type) )
            $this->type = strtoupper( $type );
    }
    
    /**
     * 获取实例
     * @param string $type              加密类型
     * @param string $secret_key        秘钥
     * @return instance
     */
    public static function instance($type=null , $secret_key=null){
        if(self::$ins instanceof self)
            return self::$ins;
        else
            return self::$ins = new self( $secret_key , $type );
    }
    
    /**
     * 设置加密参数
     * @param array $param                  参数集合
     * @param string $param['bit']          加密字节;128|192|256
     * @param string $param['aes_mode']     AES加密模式;CFB|CBC|NOFB|OFB|STREAM|ECB
     * @param string $param['rsa_mode']     RSA加密模式;PUBLIC|PRIVATE
     * @param string $param['base64]        是否使用base64二次加密；true|false
     * @param string $param['public_key']   RSA公钥
     * @param string $param['private_key]   RSA私钥
     * @return null 
     */
    public function setParam($param = array()){
        if( empty($param) ) 
            return null;
        
        //设置是否使用base64二次加密
        if( array_key_exists( 'base64' , $param ) && !$param['base64'] )
            $this->_use_base64 = false;
        
        //AES加密设置
        if( $this->type == 'AES' ){
            //允许设置的字节
            $allow_set_bits = array( 
                128     => MCRYPT_RIJNDAEL_128, 
                192     => MCRYPT_RIJNDAEL_192 , 
                256     => MCRYPT_RIJNDAEL_256 
            );
            
            //允许设置加密模式
            $allow_set_modes = array( 
                'CFB'       => MCRYPT_MODE_CFB , 
                'CBC'       => MCRYPT_MODE_CBC ,
                'NOFB'      => MCRYPT_MODE_NOFB , 
                'OFB'       => MCRYPT_MODE_OFB ,
                'STREAM'    => MCRYPT_MODE_STREAM , 
                'ECB'       => MCRYPT_MODE_ECB
            );
            
            //设置加密字节
            if( array_key_exists( 'bit' , $param ) && array_key_exists( $param['bit'] , $allow_set_bits ) )
                $this->_bit     = $allow_set_bits[$param['bit']];
            
            //设置加密模式
            if( array_key_exists( 'aes_mode' , $param) ){
                $_mode = strtoupper($param['aes_mode']);
                if( array_key_exists( $_mode , $allow_set_modes ) )
                    $this->_aes_mode = $allow_set_modes[$_mode];
            }
            
            @$this->_iv_size = mcrypt_get_iv_size( $this->_bit , $this->_aes_mode );
            @$this->_iv      = mcrypt_create_iv( $this->_iv_size , MCRYPT_RAND );
        }
            
        //RSA加密设置
        if( $this->type == 'RSA' ){
            if( array_key_exists( 'public_key' , $param ) ){
                //获取公钥
                $public_key_id = openssl_pkey_get_public( $param['public_key'] );
                
                if( $public_key_id )
                    $this->_rsa_public_key      = $public_key_id;
            }
            
            if( array_key_exists( 'private_key' , $param ) ){
                //获取私钥
                $private_key_id = openssl_pkey_get_private( $param['private_key'] );
                
                if( $private_key_id ) 
                    $this->_rsa_private_key     = $private_key_id;
            }
            
            $allow_set_modes = array( 'PUBLIC' , 'PRIVATE' ); 
            if( array_key_exists( 'rsa_mode' , $param ) && in_array( strtoupper($param['rsa_mode']) , $allow_set_modes ) )
                $this->_rsa_mode                = strtoupper($param['rsa_mode']);
        }
    }
    
    /**
     * 加密
     * @param string $data          加密数据
     * @param string $type          加密类型；aes|rsa
     * @return NULL|string
     */
    public function encode($data , $type=null){
        if( !isset($data) )
            return null;
        
        $type = isset($type) ? $type :$this->type;
        switch( strtoupper($type) ){
            case 'AES':
                $encode_result = $this->aesEncode( $data );
                break;
            case 'RSA':
                $encode_result = $this->rsaEncode( $data );
                break;
        }
        return $encode_result;
    }
    
    /**
     * 解密
     * @param string $data          解密数据
     * @param string $type          解密类型；aes|rsa
     * @return NULL|string
     */
    public function decode($data , $type=null){
        if( !isset($data) )
            return null;
        
        $type = isset($type) ? $type :$this->type;
        switch( strtoupper($type) ){
            case 'AES':
                $encode_result = $this->aesDecode( $data );
                break;
            case 'RSA':
                $encode_result = $this->rsaDecode( $data );
                break;
        }
        return $encode_result;
    }
    
    /**
     * AES加密
     * @param string $data      加密数据
     * @return string|null
     */
    private function aesEncode($data){
        if( $this->_aes_mode === MCRYPT_MODE_ECB )
            @$encode_str = mcrypt_encrypt( $this->_bit , self::$secret_key , $data , $this->_aes_mode );
        else
            @$encode_str = mcrypt_encrypt( $this->_bit , self::$secret_key , $data , $this->_aes_mode , $this->_iv );
        
        //十六进制流转字符串    
        $encode_str = $this->hexToString( $encode_str );
        
        if( $this->_use_base64 )
            $encode_str     = base64_encode($encode_str);
            
        return $encode_str;
    }
    
    /**
     * AES解密
     * @param string $string          解密数据
     * @return string|null
     */
    private function aesDecode($data){
        if( $this->_use_base64 )
            $data = base64_decode( $data );
            
        //字符串转十六进制流
        $data = $this->stringToHex( $data );
            
        if( $this->_aes_mode === MCRYPT_MODE_ECB )
            @$decode_string = mcrypt_decrypt( $this->_bit , self::$secret_key , $data , $this->_aes_mode );
        else 
            @$decode_string = mcrypt_decrypt( $this->_bit , self::$secret_key , $data , $this->_aes_mode , $this->_iv );
        
        return $decode_string;
    }
    
    /**
     * RSA加密
     * @param string $data      加密数据
     * @return string
     */
    private function rsaEncode($data){
        switch( $this->_rsa_mode ){
            //公钥加密
            case 'PUBLIC':
                $encrypt_rs = openssl_public_encrypt( $data , $crypted , $this->_rsa_public_key );
                
                openssl_free_key( $this->_rsa_public_key );
                
                if( !$encrypt_rs )
                    return false;
                break;
            //私钥加密
            case 'PRIVATE':
                $encrypt_rs = openssl_private_encrypt( $data , $crypted , $this->_rsa_private_key );
                
                openssl_free_key( $this->_rsa_private_key );
                
                if( !$encrypt_rs )
                    return false;
                break;
        }
        
        //十六进制流转字符串
        $crypted = $this->hexToString( $crypted );
        
        //base64二次加密
        if( $this->_use_base64 )
            $crypted = base64_encode( $crypted );
        
        return $crypted;
    }
    
    /**
     * RSA解密
     * @param string $data      解密数据
     * @return string
     */
    private function rsaDecode($data){
        //base64二次解密
        if( $this->_use_base64 )
            $data = base64_decode( $data );
        
        //字符串转十六进制流
        $data = $this->stringToHex( $data );
        
        switch( $this->_rsa_mode ){
            //公钥解密
            case 'PUBLIC':
                $decrypted_rs = openssl_private_decrypt( $data , $decrypted , $this->_rsa_private_key );
                
                openssl_free_key( $this->_rsa_private_key );
                
                if( !$decrypted_rs )
                    return false;
                break;
            //私钥解密
            case 'PRIVATE':
                $decrypted_rs = openssl_public_decrypt( $data , $decrypted , $this->_rsa_public_key );
                
                openssl_free_key( $this->_rsa_public_key );
                
                if( !$decrypted_rs )
                    return false;
                break;
        }
        
        return $decrypted ? $decrypted : false;
    }
    
    /**
     * 十六进制流转字符串
     * @param string $string    十六进制流 
     * @return string
     */
    private function hexToString($string){
        $buf = '';
        for ( $i = 0; $i < strlen($string); $i++ ){
            $val = dechex( ord( $string{$i} ) );
            if( strlen($val) < 2)
                $val = '0' . $val;
                $buf .= $val;
        }
        return $buf;
    }
    
    /**
     * 字符串转十六进制流
     * @param string $string    字符串 
     * @return string
     */
    private function stringToHex($string){
        $buf = '';
        for( $i = 0; $i < strlen($string); $i += 2 ){
            $val = chr(hexdec(substr($string , $i , 2)));
            $buf .= $val;
        }
        return $buf;
    }
}
?>