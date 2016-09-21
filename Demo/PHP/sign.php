<?php
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Sign;
require_once '../../PHP/Sign.class.php';

//签名秘钥
$sign_key = md5('ABC');

//盐值(签名类型：crypt 时)使用
$slat = '123456';

//签名数据
$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => '163BD1FB151BBA71F48294B508C922AF'
);

//签名对象
$sign_object = Sign::instance( $sign_key );

//签名类型：md5(crypt|sha1|base64_encode|rsa)
$sign_object::$make_sign_func = 'md5';

//获取签名数据
$sign_str   = $sign_object->getSignStr( $data );

//数据签名；md5|crypt|sha1|base64_encode|rsa
$make_sign  = $sign_object->makeSign($sign_str , null , $slat);
echo "<h3>待签名数据：</h3>";
print_r($data);
echo "<h3>签名字符串：</h3>" . $sign_str . "<br/>";
echo "<h3>签名值：</h3>" . $make_sign . "<br>";

echo "<h3>签名验证：</h3>";
$rs = $sign_object->verifySign($data['sign'] , $data , null , $slat);
if( $rs ){
    echo "Success!";
}else{
    echo "Fail!";
}
?>
