# Flower MQ Client

Flower MQ client, you can add this package to your project !

## Install

```shell
composer require mrtwenty/flower-client
```
### demo

```PHP
# pecl redis
$redis = new \Redis();
$mq    = [
    'name'       => 'mq',
    'delay_name' => 'mq_delay',
];
$client = new Client($redis, $mq);

# add queue 
$res = $client->add(['test' => 'data']);
var_dump($res);

# delay add queue
$second = 3;
$res = $client->add(['test' => 'data'], $second);
var_dump($res);

```

## Flower MQ Server

https://packagist.org/packages/mrtwenty/flower

