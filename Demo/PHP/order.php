<?php
/**
 * 分库分表订单号生成示例
 */
require_once "../../PHP/Snowflake.class.php";
require_once "../../PHP/Order.class.php";

// 用户ID
$uid = 9527;
$machine_id = 1;

// 订单服务
$orderService = new Order();

// 生成订单号
$order_no = $orderService->makeOrderNo($uid, $machine_id);

echo "订单号：".$order_no."<br/>";
echo "长度：".strlen($order_no)."<br/>";


echo str_repeat("=", 150)."<br/>";

// 唯一ID
$unique_id = Snowflake::makeUniqueId($machine_id);
echo "唯一ID：".$unique_id."<br/>";
echo "长度：".strlen($unique_id)."<br/>";

//  生成时间
$time = Snowflake::uniqueIdParseTime($unique_id);
echo "生成时间：" . date("Y-m-d H:i:s", $time/1000)."<br/>";
echo "时间戳：" . $time;

