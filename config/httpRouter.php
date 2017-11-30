<?php
/**
 * 路由配置文件
 * User: lixin
 * Date: 2017/3/26
 * Time: 下午8:10
 */
return [
    'GET' => [
        '/' => 'Index@index', //首页
        '/aboutMe' => 'Index@aboutMe', //AboutMe
        '/login' => 'Login@index', //登陆展示
        '/chat' => 'Chat@index',//聊天室
    ],
    'POST' => [
        '/signIn' => 'Login@signIn',//登陆动作
    ],
    'PUT' => [
        '/' => 'Index@index',
    ],
    'DELETE' => [
        '/' => 'Index@index',
    ]
];