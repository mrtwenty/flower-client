<?php

declare(strict_types=1);

namespace Flower;

/**
 * Flower MQ client
 * 客户端
 */
class Client
{
    protected $redis;
    protected $mq;

    /**
     * @param mixed $redis 
     * @param array $mq mq配置
     */
    public function __construct($redis, $mq)
    {
        $this->redis = $redis;
        $this->mq = $mq; //name、delay_name、fail_list
    }

    /**
     * 添加消息
     * @param mixed $data 内容
     * @param int   $delay 延迟秒数
     * @return string|int
     */
    public function add($data, $delay = 0)
    {
        if ($delay == 0) {
            $mq_name     = $this->mq['name']; //消息队列名
            $package_str = \json_encode($data, JSON_UNESCAPED_UNICODE);
            return $this->redis->xadd($mq_name, '*', ['data' => $package_str]);
        }

        $mq_delay_name = $this->mq['delay_name'];

        $now = time();

        //序列化
        $msg = [
            'id'   => random_int(PHP_INT_MIN, PHP_INT_MAX),
            'time' => $now,
            'data' => $data,
        ];
        $package_str = \json_encode($msg, JSON_UNESCAPED_UNICODE);
        $id = $now + $delay;
        if ($this->redis->zadd($mq_delay_name, $id, $package_str)) {
            return $id;
        }
        return 0;
    }

    /**
     * 确认消息
     * @param string $group_name 分组名
     * @param string $id
     */
    public function ack($group_name, $id)
    {
        $mq_name    = $this->mq['name']; //消息队列名
        return $this->redis->xAck($mq_name, $group_name, [$id]);
    }
}
