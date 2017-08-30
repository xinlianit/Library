<?php
/**
 * 订单服务
 * @desc 分库分表规则及订单号机构规则
 * 分库规则：
 *      分库规则“二叉树分库”，所谓“二叉树分库”指的是：在进行数据库扩容时，都是以2的倍数进行扩容。比如：1台扩容到2台，2台扩容到4台，4台扩容到8台，以此类推。
 *      这种分库方式的好处是，在进行扩容时，只需DBA进行表级的数据同步，而不需要自己写脚本进行行级数据同步。
 * 分表规则：
 *      分表规则分10个表，按10取模
 * 用户UID：
 *      用户UID用途：计算数据库及表编号          
 */
class Order {
    
    /**
     * 订单号版本
     * @var int
     * @desc 版本号说明；
     * 1：库编号长度=1、表编号长度=1
     * 2：库编号长度=1、表编号长度=2
     * 3：库编号长度=2、表编号长度=1
     * 4：库编号长度=2、表编号长度=2
     */
    public $version = 1;
    
    /**
     * 最大数据库数量
     * @var int
     */
    public $max_database_number = 64;
    
    /**
     * 实际数据库数量
     * @var int
     */
    public $fact_database_number = 8;
    
    /**
     * 分表数量
     * @var int
     */
    public $table_number = 10;
    
    /**
     *  生成订单号
     * @param int $uid          用户UID
     * @desc 按用户UID维度分库分表
     */
    public function makeOrderNo($uid=null, $machine_id=0){
        //UID检测
        if($uid === null || !is_numeric($uid)) return null;
        
        //最大数据库编号(1-64) = 用户UID除10取整 % 64库(最大库) + 1起始；最大支持64个库
        $max_db_no = intval(floor($uid/10)) % $this->max_database_number + 1;
        
        //实际数据库编号(1-8) = (最大数据库编号 - 1) % 8库(实际库) + 1起始；实际数据库8个
        $db_no = ($max_db_no-1) % $this->fact_database_number + 1;
        
        //表编号(0-9) = 用户UID % 10
        $table_no = $uid % $this->table_number;
        
        // 唯一码 = 时间戳 + 机器ID + 自增序列号
        $unique_id  = Snowflake::makeUniqueId($machine_id);
        
        //订单号 = 版本号(1) + 库编号(1) + 表编号(1) + 唯一码（18）
        $order_no = $this->version . $db_no . $table_no . $unique_id;
        
        return $order_no;
    }
}