<?php

use Flower\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

function redis()
{
    $redis_host = '127.0.0.1';
    $redis_port = 6379;
    $redis_auth = '123456';
    $redis_db   = 1;

    $redis = new \Redis();
    if ($redis->connect($redis_host, $redis_port) !== true) {
        throw new \Exception("redis connect error", 1);
    }

    //密码
    if ($redis_auth !== '' && $redis->auth($redis_auth) !== true) {
        throw new \Exception("redis auth error", 1);
    }

    //测试链接
    if (!$redis->ping()) {
        throw new \Exception("redis connect error", 1);
    }

    //选择数据库
    $redis->select($redis_db);
    return $redis;
}

$redis = redis();
$mq    = ['name' => 'mq', 'delay_name' => 'mq_delay'];
$client = new Client($redis, $mq);

$res = $client->add(['test' => 'data']);
var_dump($res);

$res = $client->add(['test' => 'data'], 3);
var_dump($res);
