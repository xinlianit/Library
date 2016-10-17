<?php
/**
 * RabbitMQ 消费者
 */

//交换机名称
$exchangeName = 'demo';
//队列名称
$queueName = 'hello';
//路由名称
$routeKey = 'hello';

//主机配置
$config = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest'
);

//创建连接
$connection = new AMQPConnection($config);
$connection->connect() or die("Cannot connect to the broker!\n");

//创建通道
$channel = new AMQPChannel($connection);
//创建交换机
$exchange = new AMQPExchange($channel);
//设置交换机名称
$exchange->setName($exchangeName);
//设置交换机类型（直接）
$exchange->setType(AMQP_EX_TYPE_DIRECT);

@$exchange->declare();

//创建队列
$queue = new AMQPQueue($channel);
//设置队列名称
$queue->setName($queueName);
@$queue->declare();

//绑定交换机
$queue->bind($exchangeName, $routeKey);
var_dump('[*] 等待消息...');
while (true) {
    //消费回调函数
    $queue->consume('callback');
}
//断开连接
$connection->disconnect();

//消费回调
function callback($envelope, $queue) {
        $msg = $envelope->getBody();
        var_dump(" [x] 收到:" . $msg);
        $queue->nack($envelope->getDeliveryTag());
}

