<?php 
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Sign;
require_once '../../PHP/Sign.class.php';

//签名对象
$sign_object = Sign::instance();
//签名类型：RSA
$sign_object::$make_sign_func   = 'rsa';
//RSA公钥
$sign_object::$rsa_public_key   = file_get_contents( 'rsa_key/rsa_public_key.pem' );
//RSA私钥
$sign_object::$rsa_private_key  = file_get_contents( 'rsa_key/rsa_private_key.pem' );

//签名数据
$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => 'rgg80vVrU0VjGNTCLM+RKuwwPUbk7Lo897o/++LknYh8W/Nlm61up2RGTlmqbo/86DwrD9DTOUvep13DmSpMBDWKhx/BSMKRJpapPdLlELnNRK0OwDY7K0iQ5peHNSkUuvaASqx5lQYpP4nnGOsljn2fjwB0KvOb5qg04Wgo3DI='
);

//获取签名数据
$sign_str   = $sign_object->getSignStr( $data );

//数据签名
$sign = $sign_object->makeSign( $sign_str );

$rs = $sign_object->verifySign( $data['sign'] , $data );

echo "<h3>待签名数据：</h3>";
print_r($data);

//要签名的数据
echo '<h3>签名字符串：</h3>';
echo $sign_str.'<br/>';

//签名后的数据
echo '<h3>签名值：</h3>';
echo $sign . '<br/>';

echo "<h3>签名验证：</h3>";
if( $rs ){
    echo "Success!";
}else{
    echo "Fail!";
}


?>