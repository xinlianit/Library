<?php
require_once '../../PHP/Flume.class.php';

try{
    $flume = Flume::getIns('192.168.1.180', 8888, 'tcp', 30, STREAM_CLIENT_ASYNC_CONNECT);
    
    //发送数据到 Flume-ng
    $data = '{name:"jirneyou",nick:"24K村帅-2113"}';
    $rs = $flume::push($data);
    
    var_dump($rs);
    
}catch(Exception $e){
    echo ($e->getMessage());
}