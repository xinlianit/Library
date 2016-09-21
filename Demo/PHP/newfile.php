<?php
// Rsa的公私钥
$pubkey = '—–BEGIN PUBLIC KEY—–
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCurS+gyXEGyNxKcu1Ja0L6uN7/
TBMNQw/DgicvftExXrus1zTPL5jSe/fOjAqssO52Pla0UlugIAgazYn/HLjEWvtg
Sjsqi65R+4FbC95BROKR1qhsAo2xb25EZ/Ab94khxlYBTtAfSFcT9dIXP6rmmW2w
SlnIOSSxLplfj7I4jQIDAQAB
—–END PUBLIC KEY—–
';

$prikey = '—–BEGIN RSA PRIVATE KEY—–
MIICXAIBAAKBgQDWglpJUgBrlolNz4cgRBxsD/Em8N+5g/IyFuEj0DqRG8jN8CIA
l8W/zdsPn8YBqaml9ovtySHhk2sRYWCGPTebFcS5EIrMJHtQaIv6VoaEI6hSfreK
RZ73/tMOoKivKwNBRKSLewhXdq5pc4sc00as4CrxDi1yPOk+ROyONCom9QIDAQAB
AoGAX0vbxnT6oNFowDuxAUGhCtTuQmmCSs12fJAzhxCL5ElepTbINFE41eQjLMbD
VZvFNWjZc1MGjUtLppYHJrvthlRw2dRHHd1adwy1TB1aPKvgkr/78a7YwQJrMl/1
/eIs7Ry/DqZatRCqeK0TTW6A+rlwmvtvJ2dO+mgO1DUWUwECQQD+kLSVcxqDAKY7
gyhlzDAfYQgdva4+jP1a2vXDP6A+9m7uEP/yRmUO9O/jsoE8s29ujBq6XnYA2KI8
vedny6NBAkEA17faaBMKkKNhWmSeHD+raBLc4xElQVXysvw7RPfBtjSEqouaDCBg
g1r1e054Pj+pyLWdjW0P7R7Y8FWVWHM6tQJAPKs6DoAfKmeGNpq8jv5J3cCfUY86
LrglTXjvp3fLdhX/PAebKB90yErBvU92k4PkI8GKQS5wCyWWDMnpk4gpwQJABUUP
h9VXP7tOCIhGuIfxpwQ28zEbCOKRoD+7Lu8ig1H7H7NzWvJ7iRnyv0VmeJbTjfyp
0aelaPSE9jIRCO0ftQJBAKTyfoe6v6WjRmSQql2CVMCJ/0SyAjduJyzJDBLPgs+V
i0s+73mVYJihdjkA9chHKJwqJ0JIMvxUXNt2VTgwUVE=
—–END RSA PRIVATE KEY—–
';

$site_id='95184';
$order_id='123';
$order_time='20120307120000';
$user_id='61981700';
$urs='someone@163.com';
$reason='1';
$pts='150';

$sign=$site_id.$user_id.$order_id.$order_time.$urs.$reason.$pts;

$sign=sha1($sign);

$res = openssl_pkey_get_private($prikey);

if (openssl_sign($sign, $out, $res))
$sign=bin2hex($out);

$url=”http://esalesdev.163.com:8002/script/interface/dc_input?site_id=”.$site_id.”&order_id=”.$order_id.”&order_time=”.$order_time.”&user_id=”.$user_id.”&urs=”.$urs.”&reason=”.$reason.”&pts=”.$pts.”&sign=”.$sign;
print_r($url);

?>