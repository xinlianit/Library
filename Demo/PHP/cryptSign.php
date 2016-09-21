<?php 
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Sign;
require_once '../../PHP/Sign.class.php';

//签名数据
$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => '88GLYB7K/HZ5.'
);

//签名秘钥
$secret_key = md5('ABC');

//签名盐值
$slat = '888888';

//签名对象
$sign_object = Sign::instance( $secret_key );

//加密类型crypt
$sign_object::$make_sign_func = 'crypt';

//获取签名数据
$sign_str   = $sign_object->getSignStr( $data );

//数据签名
$sign = $sign_object->makeSign( $sign_str , null , $slat );

//签名验证
$verify_result = $sign_object->verifySign( $data['sign'] , $data , null , $slat );


echo "<h3>待签名数据：</h3>";
print_r($data);

//要签名的数据
echo '<h3>签名字符串：</h3>';
echo $sign_str.'<br/>';

//签名后的数据
echo '<h3>签名值：</h3>';
echo $sign . '<br/>';

echo "<h3>签名验证：</h3>";
if( $verify_result ){
    echo "Success!";
}else{
    echo "Fail!";
}


?>