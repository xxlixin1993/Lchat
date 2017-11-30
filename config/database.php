<?php
/**
 * 数据库配置
 * User: lixin
 * Date: 2017/3/26
 * Time: 下午10:26
 */
return [
    'chat' => [
        'write' => [
            'host' => env('DB_WRITE_HOST', '127.0.0.1'),
            'username' => env('DB_WRITE_USER', 'root'),
            'password' => env('DB_WRITE_PASSWD', '123456'),
        ],
        'read' => [
            [
                'host' => env('DB_READ1_HOST', '127.0.0.1'),
                'username' => env('DB_READ1_USER', 'root'),
                'password' => env('DB_READ1_PASSWD', '123456'),
            ],
            [
                'host' => env('DB_READ2_HOST', '127.0.0.1'),
                'username' => env('DB_READ2_USER', 'root'),
                'password' => env('DB_READ2_PASSWD', '123456'),
            ],
        ],
        'database' => env('DATABASE', 'chat'),
        'driver' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],

    'test' => [
        'write' => [
            'host' => env('DB_WRITE_HOST', '127.0.0.1'),
            'username' => env('DB_WRITE_USER', 'root'),
            'password' => env('DB_WRITE_PASSWD', '123456'),
        ],
        'read' => [
            [
                'host' => env('DB_READ1_HOST', '127.0.0.1'),
                'username' => env('DB_READ1_USER', 'root'),
                'password' => env('DB_READ1_PASSWD', '123456'),
            ],
            [
                'host' => env('DB_READ2_HOST', '127.0.0.1'),
                'username' => env('DB_READ2_USER', 'root'),
                'password' => env('DB_READ2_PASSWD', '123456'),
            ],
        ],
        'database' => env('DATABASE', 'test'),
        'driver' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
];
