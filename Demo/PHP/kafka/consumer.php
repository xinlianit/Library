<?php
/**
 * kafka for php 消费者
 * @todo https://github.com/nmred/kafka-php
 */

//加载依赖包
require_once('./vendor/autoload.php');
 
//获取需要处理的partitionId
$partitionId = 0;
 
//消费者实例
$consumer = \Kafka\Consumer::getInstance('localhost:2181');
 
//设置消费组组
$consumer->setGroup('test-consumer-group');

//消息通道名称 
$topicName = 'test';

//设置消息通道分区
$consumer->setPartition( $topicName , $partitionId );

//设置是否启用偏移量
$consumer->setFromOffset(true);

//设置获取消息最大字节
$consumer->setMaxBytes(102400);
 
while(true){
    //获取消息
    $topic = $consumer->fetch();
 
    foreach ($topic as $topicName => $partition) {
        foreach ($partition as $partId => $messageSet) {
            foreach ($messageSet as $message) {
                //打印消息
                echo "接收消息：" . ((string)$message) . "\n";
            }
        }
    }
    //echo "consumer sleeping\n";
    sleep(1);
}