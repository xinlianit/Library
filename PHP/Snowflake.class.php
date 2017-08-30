<?php
/**
 * Snowflake 唯一ID生成服务
 */
abstract class Snowflake {
    /**
     * 开始时间戳(毫秒：ms) 2017-01-01 00:0:00
     */
    const START_EPOCH = 1483200000000;
    
    /**
     * 最大时间戳(毫秒：ms) 2014-01-01 00:0:00
     */
    const MAX_TIME_STAMP = 1388505600000;
    
    /**
     * 最大随机数；1毫秒最多可以产生随机数4096个 (0-4095)；4095 = 1111 1111 1111(12位正常)，4096 = 1000 0000 0000 0（13位超出） 
     * 1秒并发 = 4096 * 1000 = 4096000（409万6千）
     */
    const MAX_RANDOM_NUMBER = 4095;
    
    /**
     * 符号位（不使用） 0：正数、1：负数
     */
    const MARK_BITS = 0;
    
    /**
     * 生成唯一ID
     * @param int $machine_id   机器配置ID；最多支持1024台机器，0-1023；1023 = 1111 1111 11(10位正常)，1024 = 1000 0000 000（11位超出） 
     * @return int              唯一码
     * @throws Exception        1、机器号溢出
     */
    public static function makeUniqueId($machine_id=0){
        // 机器ID校验
        if(!is_numeric($machine_id) || intval($machine_id) < 0 || intval($machine_id) > 1023){
            throw new Exception("machine id overflow!");
        }
        
        // 当前时间戳(ms)
        $now_time_stamp = floor(microtime(true) * 1000);
        
        // 运行时长 = 当前时间戳 - 开始时间戳
        $time_long = $now_time_stamp - self::START_EPOCH;
        
        // 41bits 时间戳 = 运行时长 + 最大时间戳
        $time_stamp_41bits = decbin($time_long + self::MAX_TIME_STAMP);
        
        // 10bits 机器配置ID
        $machine_id_10bits = str_pad(decbin($machine_id), 10, "0", STR_PAD_LEFT);
        
        // 12bits 随机序列号
        $random_number_12bits = str_pad(decbin(mt_rand(0, self::MAX_RANDOM_NUMBER)), 12, "0", STR_PAD_LEFT);
        
        // 64bits 结果码 = 符号位（1） '+' 时间戳（41） '+' 机器ID（10） '+' 自增序号（12）
        $result_64bits = self::MARK_BITS . $time_stamp_41bits . $machine_id_10bits . $random_number_12bits;
        
        // 返回十进制数
        return bindec($result_64bits);
    }
    
    /**
     * 唯一码解析出生成时间戳
     * @param int $unique_id           唯一码
     * @return int                     时间戳（毫秒：ms）
     * @throws Exception               1、唯一码错误
     */
    public static function uniqueIdParseTime($unique_id=null) {
        // 唯一码校验
        if(!is_numeric($unique_id)){
            throw new Exception("Unqiue Id Error!");
        }
        
        // 还原 64 bits 二进制码
        $result_64bits = decbin($unique_id);
        
        // 获取生成时间戳(毫秒：ms) = 41bits时间戳 - 最大时间戳 + 开始时间戳
        $time_stamp = bindec(substr($result_64bits, 0, 41)) - self::MAX_TIME_STAMP + self::START_EPOCH;
        
        return $time_stamp;
    }
}