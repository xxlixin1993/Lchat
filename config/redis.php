<?php
/**
 * redis.php
 * redis配置文件
 * User: lixin
 * Date: 17-11-28
 */
return [
    'testServer' => [
        'host' => env('REDIS_HOST_1', '127.0.0.1'),
        'port' => env('REDIS_PORT_1', 6379),
        'password' => env('REDIS_PASSWD_1', 123456)
    ]
];