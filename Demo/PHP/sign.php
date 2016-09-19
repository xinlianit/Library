<?php
header("Content-type: text/html; charset=utf-8");
use \Lib\Sign;
require_once '../Lib/Sign.class.php';

//签名秘钥
$sign_key = '123456789';

//签名数据
$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => '50C21E634016CFCCA6042A78A74EB49E'
);

$sign_object = Sign::instance( $sign_key );

$sign_str   = $sign_object->getSignStr( $data );

//md5|crypt|sha1|base64_encode
$make_sign  = $sign_object->makeSign($sign_str);
echo "<h3>待签名数据：</h3>";
print_r($data);
echo "<h3>签名字符串：</h3>" . $sign_str . "<br/>";
echo "<h3>签名值：</h3>" . $make_sign . "<br>";

echo "<h3>签名验证：</h3>";
$rs = $sign_object->verifySign($data['sign'] , $data);
if( $rs ){
    echo "Success!";
}else{
    echo "Fail!";
}
?>
