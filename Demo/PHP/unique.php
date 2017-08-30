<?php
/**
 * 分布式全局唯一ID生成示例
 */
require_once "../../PHP/Snowflake.class.php";

// 机器配置ID
$machine_id = 1;

// 生成唯一ID
$unique_id  = Snowflake::makeUniqueId($machine_id);
echo "唯一ID（UID）:".$unique_id."<br/>长度：".strlen($unique_id);

echo "<br/>";

// 获取唯一号生成时间
$time = Snowflake::uniqueIdParseTime($unique_id);
echo "生成时间：".date("Y-m-d H:i:s", $time/1000)."<br/>时间戳(ms)：".$time;
