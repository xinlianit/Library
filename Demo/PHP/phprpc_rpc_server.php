<?php 
/**
 * PHPRPC 服务提供者
 */
require_once 'phprpc/phprpc_server.php';

class RpcServer {
    public static function test($param=null){
        $data = array(
            'title'     => 'RPC测试',
            'desc'      => '这是个静态方法',
            'param'     => $param
        );
        return $data;
    }
    
    public function test1($param=null){
        $data = array(
            'title'     => 'RPC测试',
            'desc'      => '这是第1个接口',
            'param'     => $param
        );
        return $data;
    }  
    
    public function test2($param=null){
        $data = array(
            'title'     => 'RPC测试',
            'desc'      => '这是第2个接口',
            'param'     => $param
        );
        return $data;
    }
    
    public function test3($param=null){
        $data = array(
            'title'     => 'RPC测试',
            'desc'      => '这是第3个接口',
            'param'     => $param
        );
        return $data;
    }
}

//实例化类
$object = new RpcServer();

//实例化RPC服务端
$rpc_server = new PHPRPC_Server();

//注册函数
$rpc_server->add( 'test' , 'RpcServer' );
$rpc_server->add( 'test1' , $object );
$rpc_server->add( 'test2' , $object );
$rpc_server->add( 'test3' , $object );

//启动服务
$rpc_server->start();
?>