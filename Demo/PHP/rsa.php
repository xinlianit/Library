<?php 
header("Content-type: text/html; charset=utf-8");
use Library\PHP\Encrypt;
require_once '../../PHP/Encrypt.class.php';

//要加密的数据
echo '<h3>要加密的数据：</h3>';
echo '你好！<br/>';
$data           = '你好！';

//Rsa对象
$Rsa = Encrypt::instance( 'rsa' );

//设置参数
$param = array(
    //使用私钥加密
    'rsa_mode'          => 'PRIVATE',
    //公钥
    'public_key'        => file_get_contents( 'rsa_key/rsa_public_key.pem' ),
    //私钥
    'private_key'       => file_get_contents( 'rsa_key/rsa_private_key.pem' ),
    //使用base64二次加密
    'base64'            => true
);

$Rsa->setParam( $param );

//Rsa加密
$result = $Rsa->encode( $data );
echo '<h3>加密后的数据：</h3>';
echo $result . '<br/>';

//Rsa解密
$result1 = $Rsa->decode( $result );
echo '<h3>解密后的数据：</h3>';
echo $result1 . '<br/>';

?>
