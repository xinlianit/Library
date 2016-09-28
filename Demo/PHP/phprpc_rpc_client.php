<?php
/**
 * PHPRPC 服务消费者
 */
require_once 'phprpc/phprpc_client.php';

//PRC服务端地址
$server_url = 'http://www.library.my/Demo/PHP/phprpc_rpc_server.php';

//实例化PRC客户端
$rpc_client = new PHPRPC_Client( $server_url );

//调用服务端的方法
$result = $rpc_client->test( array('test') );
$result1 = $rpc_client->test1( array('test') );

var_dump($result);
var_dump($result1);
