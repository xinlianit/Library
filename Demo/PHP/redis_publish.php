<?php
/**
 * 消息发布
 */
header("Content-type: text/html; charset=utf-8");
use Library\PHP\RedisService;
require_once '../../PHP/RedisService.class.php';

//频道名称
$channel_name = 'shop_id_1';

//消息
$message = "你好！".mt_rand(1000,9999);

//Redis实例
$redisServer = RedisService::instance( '192.168.3.100' , '6379' , 'redis123' );

//发布消息
$publish_result = $redisServer->publish( $channel_name , $message );

if( $publish_result ){
    echo "send Success !\n";
}else{
    echo "send Fail!\n";
}