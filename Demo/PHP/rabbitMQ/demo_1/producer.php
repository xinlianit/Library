<?php
/**
 * RabbitMQ 生产者
 */

//交换机名称
$exchangeName = 'demo';
//队列名称
$queueName = 'hello';
//路由名称
$routeKey = 'hello';
//发送消息
$message = @$_GET['data'] ? $_GET['data'] : 'Hello World!';

//主机配置
$config = array(
    'host' => '127.0.0.1', 
    'port' => '5672', 
    'vhost' => '/', 
    'login' => 'guest', 
    'password' => 'guest'
);

//创建连接
$connection = new AMQPConnection( $config );
$connection->connect() or die("无法连接到代理!\n");

try {
        //创建通道
        $channel = new AMQPChannel($connection);
        //创建交换机
        $exchange = new AMQPExchange($channel);
        //设置交换机名称
        $exchange->setName($exchangeName);
        //创建队列
        $queue = new AMQPQueue($channel);
        //设置队列名称
        $queue->setName($queueName);
        
        for($i=0;$i<1000;$i++){
            $data = $message.date('Y-m-d H:i:s',time());
            //发布消息
            $exchange->publish($data , $routeKey);
            var_dump("[x] 发送数据： " . $data);
            sleep(1);
        }
} catch (AMQPConnectionException $e) {
        var_dump($e);
        exit();
}

//断开连接
$connection->disconnect();


