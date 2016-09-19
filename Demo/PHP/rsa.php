<?php 
header("Content-type: text/html; charset=utf-8");
use Lib\Encrypt;
require_once '../Lib/Encrypt.class.php';

//加密秘钥
$secret_key     = md5('ABC');
echo '<h3>秘钥：</h3>';
echo md5('ABC').'<br/>';

//要加密的数据
echo '<h3>要加密的数据：</h3>';
echo '你好！<br/>';
$data           = '你好！';

//AES对象
$Aes = Encrypt::instance( $secret_key , 'rsa' );

//AES加密
$result = $Aes->encode( $data );
echo '<h3>加密后的数据：</h3>';
echo $result . '<br/>';

//AES解密
$result1 = $Aes->decode( $result );
echo '<h3>解密后的数据：</h3>';
echo $result1 . '<br/>';

?>
