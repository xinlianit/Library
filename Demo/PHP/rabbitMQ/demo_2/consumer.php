<?php    
/**
 * RabbitMQ 消费者
 */

//配置信息  
$conn_args = array(  
    'host' => '127.0.0.1',   
    'port' => '5672',   
    'login' => 'guest',   
    'password' => 'guest',  
    'vhost'=>'/'  
);   

//交换机名称
$e_name = 'e_linvo';
//队列名称
$q_name = 'q_linvo';
//路由键key
$k_route = 'key_1';
  
//创建连接和channel  
$conn = new AMQPConnection($conn_args);    
if (!$conn->connect()) {    
    die("Cannot connect to the broker!\n");    
}    
$channel = new AMQPChannel($conn);    
  
//创建交换机     
$ex = new AMQPExchange($channel);    
$ex->setName($e_name);  //设置交换机名称
$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型   
$ex->setFlags(AMQP_DURABLE); //持久化  
echo "Exchange Status:".@$ex->declare()."\n";    
    
//创建队列     
$q = new AMQPQueue($channel);  
$q->setName($q_name);    
$q->setFlags(AMQP_DURABLE); //持久化   
echo "Message Total:".@$q->declare()."\n";    
  
//绑定交换机与队列，并指定路由键  
echo 'Queue Bind: '.$q->bind($e_name, $k_route)."\n";  
  
//阻塞模式接收消息  
echo "Message:\n";    
while(True){  
    $q->consume('processMessage');    
    //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答   
}
//断开连接
$conn->disconnect();    
  
/** 
 * 消费回调函数 
 * 处理消息 
 */  
function processMessage($envelope, $queue) {  
    $msg = $envelope->getBody();  
    echo $msg."\n"; //处理消息  
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答  
}