<?php 
/** 
 * 参考地址：http://blog.csdn.net/clh604/article/details/20224735
 * */
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Encrypt;
require_once '../../PHP/Encrypt.class.php';

//Rsa对象
$Rsa = Encrypt::instance( 'rsa' );

//设置参数
$param = array(
    //使用私钥加密；private|public
    'rsa_mode'          => 'private',
    //公钥
    'public_key'        => file_get_contents( 'rsa_key/rsa_public_key.pem' ),
    //私钥
    'private_key'       => file_get_contents( 'rsa_key/rsa_private_key.pem' ),
    //使用base64二次加密
    'base64'            => true
);

//设置加密参数
$Rsa->setParam( $param );

//要加密的数据
$data           = '你好！';

//Rsa加密
$encrypt_result = $Rsa->encode( $data );

//Rsa解密
$decrypt_result = $Rsa->decode( $encrypt_result );


echo '<h3>要加密的数据：</h3>';
echo $data.'<br/>';

echo '<h3>加密后的数据：</h3>';
echo $encrypt_result . '<br/>';

echo '<h3>解密后的数据：</h3>';
echo $decrypt_result . '<br/>';

?>
