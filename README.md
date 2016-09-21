<h3>程序仓库</h3>

<dl>
	<dt style="font-style:normal;">目录列表</dt>
	<dd>
		<dl>
			<dt>Demo-------------------------------------------------------------示例</dt>
			<dd>
				<dl>
					<dt>PHP--------------------------------------------------------------PHP库示例</dt>
					<dd>sign.php---------------------------------------------------------数据签名 & 验签</dd>
					<dd>cryptSign.php----------------------------------------------------crypt数据签名 & 验签</dd>
					<dd>rsaSign.php------------------------------------------------------RSA数据签名 & 验签</dd>
					<dd>aes.php----------------------------------------------------------aes加密解密</dd>
					<dd>rsa.php----------------------------------------------------------rsa加密解密</dd>
				</dl>
			</dd>
		</dl>
	</dd>
	<dd>
		<dd>
				<dl>
					<dt>PHP--------------------------------------------------------------PHP程序库</dt>
					<dd>Curl.class.php---------------------------------------------------CURL 类</dd>
					<dd>Encrypt.class.php------------------------------------------------（AES & RSA）加密类</dd>
					<dd>RedisService.class.php-------------------------------------------Redis 类</dd>
					<dd>Sign.class.php---------------------------------------------------（RSA & md5|crypt|sha1|base64_encode）签名&验签类</dd>
				</dl>
			</dd>
	</dd>
	<dd>Python-----------------------------------------------------------Python程序库</dd>
	<dd>Java--------------------------------------------------------------Java程序库</dd>
</dl>


<h3>使用说明：</h3>
<h4>######################################## RSA签名 & 验签	########################################</h4>
<pre>
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

//验证签名
$verify_result = $sign_object->verifySign( $data['sign'] , $data );

if( $verify_result ){
	//签名验证成功
}else{
	//签名验证失败
}
</pre>

<h4>######################### md5|sha1|base64_encode 签名 & 验签	##########################</h4>
<pre>
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
    'sign'      => '163BD1FB151BBA71F48294B508C922AF'
);

//签名秘钥
$secret_key	= md5('ABC');

//签名类实例；$secret_key：设置秘钥、$sign_key_name：设置签名字段名（默认：sign）、$secret_key_name：设置秘钥键名（默认：key）
$signObject	= Sign::instance( $secret_key );

//签名类型(默认：md5)：crypt|sha1|base64_encode|rsa
$signObject::$make_sign_func = 'md5';

//获取签名数据
$sign_str	= $signObject->getSignStr( $data );

//生成签名
$sign	= $signObject->makeSign( $sign_str );

//验证签名
$verify_result = $signObject->verifySign( $data['sign'] , $data );

if( $verify_result ){
	//签名验证成功
}else{
	//签名验证失败
}
</pre>

<h4>################################# crypt签名 & 验签	##################################</h4>
<pre>
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

if( $verify_result ){
	//签名验证成功
}else{
	//签名验证失败
}
</pre>

<h4>################################# AES加密 & 解密	##################################</h4>
<pre>
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
</pre>

<h4>################################# RSA加密 & 解密	##################################</h4>
<pre>
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
</pre>








































