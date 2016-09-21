<?php
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Curl;
require_once '../../PHP/Curl.class.php';

//请求地址
$url = 'http://www.baidu.com';

//请求数据
$data = array(
    'name'      => 'jirenyou',
    'nick'      => '24K'
);

//Curl对象；$url：请求地址、$return：是否需要返回
$curlObject = Curl::getIns( $url );

//POST请求
$post_result    = $curlObject->post( $data );

//GET请求
$get_result     = $curlObject->get( $data );

var_dump( $post_result );
var_dump( $get_result );

?>

