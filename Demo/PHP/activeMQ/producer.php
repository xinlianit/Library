<?php
/**
 * ActiveMQ 生产者
 */

//队列名称
$queue  = '/topic/phptest';

//消息体
$msg    = @$_GET['data'];
 
try {
    //stomp实例   
    $stomp = new Stomp('tcp://localhost:61613');
    
    //发送数据
    $stomp->send($queue , date("Y-m-d H:i:s") . " " . $msg);
} catch(StompException $e) {
    //捕获异常信息
    die('Connection failed: ' . $e->getMessage());
}

?>
