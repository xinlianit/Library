<?php 
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Encrypt;
require_once '../../PHP/Encrypt.class.php';

//加密秘钥
$secret_key     = md5('ABC');

//AES对象
$Aes = Encrypt::instance( 'aes' , $secret_key );

//配置AES参数
$config = array(
    //加密字节；128|192|256
    'bit'       => 128,
    //加密模式；CFB|CBC|NOFB|OFB|STREAM|ECB
    'aes_mode'      => 'ecb',
    //使用base64二次加密
    'base64'    => true
);
$Aes->setParam( $config );

//加密数据
$data           = '你好！';

//AES加密
$encrypt_result = $Aes->encode( $data );

//AES解密
$decrypt_result = $Aes->decode( $encrypt_result );


echo '<h3>秘钥：</h3>';
echo md5('ABC').'<br/>';

//要加密的数据
echo '<h3>要加密的数据：</h3>';
echo $data.'<br/>';

echo '<h3>加密后的数据：</h3>';
echo $encrypt_result . '<br/>';

echo '<h3>解密后的数据：</h3>';
echo $decrypt_result . '<br/>';

?>
