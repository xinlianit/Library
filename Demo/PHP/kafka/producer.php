<?php
/**
 * kafka for php 生产者
 * @todo https://github.com/nmred/kafka-php
 */

//加载依赖包
require_once('./vendor/autoload.php');
 
//生产者实例
$produce = \Kafka\Produce::getInstance('localhost:2181', 3000);
 
//设置请求模式
$produce->setRequireAck(-1);
 
//消息通道名称
$topicName = 'test';

//消息内容体
$message = json_encode(array(
    'uid'   => 1,
    'name'  => 'jirenyou',
    'desc'  => 'test send',
    'time'  => time()
));

//获取到topic下可用的partitions
$partitions = $produce->getAvailablePartitions($topicName);
$partitionCount = count($partitions);

//发送需要处理的partitionId
$partitionId = 0;

//设置消息
$produce->setMessages( $topicName , $partitionId, array($message));

//发送消息
$result = $produce->send();

foreach( $result[$topicName] as $v){
    echo "--------------发送结果-----------------<br/>";
    echo "errCode:" . $v['errCode'] ."<br/>";
    echo "offset:" . $v['offset'] ."<br/>";
}

