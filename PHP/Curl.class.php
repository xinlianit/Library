<?php
/**
 * Curl类
 */
namespace Lib;

class Curl {
    /**
     * @desc 自身实例
     * @var instance
     */
    private static $Ins;
    
    /**
     * @desc 请求Url
     * @var string
     */
    private static $url;
    
    /**
     * @desc Curl 句柄
     * @var resource
     */
    private static $cul = null;
    
    public function __destruct(){
        $this->closeCurl();
    }
    
    /**
     * 获取自身对象
     * @param string $url           请求地址
     * @param boolean $return       是否需要返回值
     * @return instance
     */
    public static function getIns($url=null , $return=true){
        //初始化curl
        self::$cul = curl_init();
        //设置是否需要返回
        curl_setopt( self::$cul , CURLOPT_RETURNTRANSFER , $return ? 1 : 0 );
        
        if( $url )
            self::$url = $url;
        
        if(self::$Ins instanceof self){
            return self::$Ins;
        }else{
            return self::$Ins = new self();
        }
    }
    
    /**
     * curl POST请求
     * @param array $data       请求数据
     * @param string $url       请求地址
     * @return mixed            
     */
    public function post($data=array() , $url=null){
        //curl提交地址
        curl_setopt( self::$cul , CURLOPT_URL , $url ? $url : self::$url );
        //设置POST表单；application/x-www-from-urlencoded
        curl_setopt( self::$cul , CURLOPT_POST , 1 );
        //POST数据
        curl_setopt( self::$cul , CURLOPT_POSTFIELDS , $data );
        	
        //执行curl
        $output = curl_exec( self::$cul );
        
        return $output;
    }
    
    /**
     * curl GET请求
     * @param array $data       请求数据
     * @param unknown $url      请求地址
     * @return mixed
     */
    public function get($data=array() , $url=null){
        //参数拼接
        if( !empty($data) ){
            $param_str_arr = array();
            foreach($data as $k=>$v){
                array_push( $param_str_arr , $k .'='. $v);
            }
            if( !empty($param_str_arr) )
                $params = implode( '&' , $param_str_arr);
        }
        
        $url = $url ? $url : self::$url;
        
        //url参数分析
        $exists_param = strpos($url , '?');
        if( $exists_param === false ){
            $url .= '?' . $params;
        }else{
            $url .= '&' . $params;
        }
        
        //curl提交地址
        curl_setopt( self::$cul , CURLOPT_URL , $url );
        //执行curl
        $output = curl_exec(self::$cul);
        
        return $output;
    }
    
    /**
     * 关闭curl句柄
     */
    public function closeCurl(){
        @curl_close(self::$cul);
    }
}
?>
