<?php
/**
 * ActiveMQ 消费者
 */

//队列名称
$queue  = '/topic/phptest';

try {
    //stomp实例
    $stomp = new Stomp('tcp://localhost:61613');
    
    //订阅消息
    $stomp->subscribe($queue);
    
    while (true) {
        if ($stomp->hasFrame()) {
            //读取消息
            $frame = $stomp->readFrame();
            if ($frame != NULL) {
                //输出消息内容
                echo $frame->body . "\n";
                //确认消息已经消费
                $stomp->ack($frame);
            }
            
            //程序睡眠1微秒
            sleep(1);
        }
        else {
            print "No frames to read\n";
        }
    }
} catch(StompException $e) {
    //捕获异常信息
    die('Connection failed: ' . $e->getMessage());
}

?>
