<h3>程序仓库</h3>

<dl>
	<dt style="font-style:normal;">目录列表</dt>
	<dd>
		<dl>
			<dt>Demo-------------------------------------------------------------示例</dt>
			<dd>
				<dl>
					<dt>PHP--------------------------------------------------------------PHP库示例</dt>
					<dd>sign.php---------------------------------------------------------数据签名</dd>
					<dd>aes.php----------------------------------------------------------aes加密解密</dd>
					<dd>rsa.php----------------------------------------------------------rsa加密解密</dd>
				</dl>
			</dd>
		</dl>
	</dd>
	<dd>PHP--------------------------------------------------------------PHP程序库</dd>
	<dd>Python-----------------------------------------------------------Python程序库</dd>
	<dd>Java--------------------------------------------------------------Java程序库</dd>
</dl>


<h3>使用说明：</h3>
<h4>################################	MD5签名 & 验签	################################</h4>
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








































