<?php
require_once '../../PHP/Flume.class.php';

try{
    $flume = Flume::getIns('192.168.1.180', 8888, 'tcp', 30, STREAM_CLIENT_ASYNC_CONNECT);
    
    //发送数据到 Flume-ng
    @$msg = $_GET['data'] ? $_GET['data'] : "test";
    $data = '{data:"' . $msg . '"}';
    $rs = $flume::push($data);
    
    //发送flume失败
    if(!$rs){
        //本地调用日志接口写入，错误日志
    }
    
    var_dump($rs);
    
}catch(Exception $e){
    echo ($e->getMessage());
}