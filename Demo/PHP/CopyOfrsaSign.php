<?php 
header("Content-type: text/html; charset=utf-8");

/*

use Library\PHP\Sign;
require_once '../../PHP/Sign.class.php';

//签名秘钥
$private_key    = file_get_contents( 'rsa_key/rsa_private_key.pem' );
$public_key     = file_get_contents( 'rsa_key/rsa_public_key.pem' );

function sign($data){
    global $private_key;
    $priv_key_id = openssl_pkey_get_private( $private_key );
    
    $sign_rs = openssl_sign($data, $signature, $priv_key_id);
    
    openssl_free_key( $priv_key_id );
    
    $signature = base64_encode( $signature );
    
    return $signature;
}

function verify($data,$sign){
    global $public_key;
    $pub_key_id = openssl_pkey_get_public( $public_key );
    
    $sign = base64_decode( $sign );
    
    $verify_rs = openssl_verify($data, $sign, $pub_key_id);
    
    openssl_free_key( $pub_key_id );
    
    return $verify_rs;
}

$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => 'RGG80VVRU0VJGNTCLM+RKUWWPUBK7LO897O/++LKNYH8W/NLM61UP2RGTLMQBO/86DWRD9DTOUVEP13DMSPMBDWKHX/BSMKRJPAPPDLLELNNRK0OWDY7K0IQ5PEHNSKUUVAASQX5LQYPP4NNGOSLJN2FJWB0KVOB5QG04WGO3DI='
);

$rsaSign = Sign::instance();

$sign_str = $rsaSign->getSignStr( $data );

echo $sign_str;

$sign = sign( $sign_str );

echo "<h3>签名结果</h3>".$sign;

echo "<h3>验签结果</h3>".verify( $sign_str , $sign );

 */





use Library\PHP\Sign;
require_once '../../PHP/Sign.class.php';

//签名秘钥
$private_key    = file_get_contents( 'rsa_key/rsa_private_key.pem' );
$public_key     = file_get_contents( 'rsa_key/rsa_public_key.pem' );

//签名数据
$data = array(
    'name'      => 'jirenyou',
    'sex'       => 1,
    'age'       => 88,
    'nick'      => '风一样的男人',
    'phone'     => '',
    'hobby'     => json_encode(array('b','c','a','g','e')),
    'descript'  => '帅哥一个",不解释！&&%￥！~~~',
    'sign'      => 'RGG80VVRU0VJGNTCLM+RKUWWPUBK7LO897O/++LKNYH8W/NLM61UP2RGTLMQBO/86DWRD9DTOUVEP13DMSPMBDWKHX/BSMKRJPAPPDLLELNNRK0OWDY7K0IQ5PEHNSKUUVAASQX5LQYPP4NNGOSLJN2FJWB0KVOB5QG04WGO3DI='
);

//签名对象
$sign_object = Sign::instance();
//签名类型：RSA
$sign_object::$make_sign_func   = 'rsa';
//RSA公钥
$sign_object::$rsa_public_key   = $public_key;
//RSA私钥
$sign_object::$rsa_private_key  = $private_key;

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