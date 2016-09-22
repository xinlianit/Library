<?php 
/**
 * 消息订阅
 */
header("Content-type: text/html; charset=utf-8");
use Library\PHP\RedisService;
require_once '../../PHP/RedisService.class.php';

//频道名称
$channel_name = 'shop_id_1';

//Redis实例
$redisServer = RedisService::instance( '192.168.3.100' , '6379' , 'redis123' );


//频道名称
$channel_name = 'shop_id_1';

try{
    //订阅消息
    $message = $redisServer->subscribe( array($channel_name) , 'outputMsg');
}catch(Exception $e){
    var_dump($e);
    exit("error!");
}

//输出消息
function outputMsg($redis , $channel , $message){
      echo $channel , "==>" , $message , PHP_EOL;
}

?>