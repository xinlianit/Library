<?php 
// require_once './vendor/autoload.php';

// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;
// use Monolog\Handler\FirePHPHandler;

// // 创建Logger实例
// $logger = new Logger('my_logger');
// // 添加handler
// $logger->pushHandler(new StreamHandler('./monolog.log', Logger::DEBUG));
// $logger->pushHandler(new FirePHPHandler());

// // 开始使用
// $logger->addInfo('My logger is now ready');
// $logger->addInfo('Adding a new user', array('username' => 'Seldaek'));
function test($data){
    if(!$data){
        throw new Exception("参数为空！");
        return null;
    }
    return $data;
}

try{
    test();
}catch(Exception $e){
    $code               = $e->getCode();
    $file               = $e->getFile();
    $line               = $e->getLine();
    $message            = $e->getMessage();
    $previous           = $e->getPrevious();
    $trace              = $e->getTrace();
    $traceAsString      = $e->getTraceAsString();
    $string = $e->__toString();
    echo $traceAsString."<br/>";
    echo $code."<br/>";
    echo $file."<br/>";
    echo $line."<br/>";
    echo $message."<br/>";
    echo $previous."<br/>";
    echo "<pre/>";
    print_r($trace) ."<br/>";
    //print_r($traceAsString);
    echo $string;
}
?>