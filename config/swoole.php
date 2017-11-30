<?php
/**
 * swoole.php
 * swoole配置文件
 * User: lixin
 * Date: 17-11-22
 */
return [
    'http' => [
        // #设置POST消息解析开关，选项为true时自动将Content-Type为x-www-form-urlencoded的请求包体解析到POST数组。设置为false时将关闭POST解析。
        'http_parse_post' => env('http_parse_post'),
        // 在Server启动时自动将master进程的PID写入到文件，在Server关闭时自动删除PID文件
        'pid_file' => BASEDIR . '/log/http.pid',
        // 加入此参数后，执行php server.php将转入后台作为守护进程运行
        'daemonize' => env('daemonize') ? true : false,
        // 指定swoole错误日志文件
        'log_file' => BASEDIR . '/log/httpSwoole.log',
    ],
    'tcp' => [
        // 此参数表示worker进程在处理完n次请求后结束运行。manager会重新创建一个worker进程。此选项用来防止worker进程内存溢出。
        'max_request' => 5000,
        // 设置启动的worker进程数量。swoole采用固定worker进程的模式。PHP代码中是全异步非阻塞，worker_num配置为CPU核数的1-4倍即可。
        // 如果是同步阻塞，worker_num配置为100或者更高，具体要看每次请求处理的耗时和操作系统负载状况。
        'worker_num' => 4,
        // 通过此参数来调节poll线程的数量，以充分利用多核 默认设置为CPU核数
        'reactor_num' => 2,
        // 加入此参数后，执行php server.php将转入后台作为守护进程运行
        'daemonize' => env('daemonize') ? true : false,
        // 此参数将决定最多同时有多少个待accept的连接
        'backlog' => 128,
        // 每隔多少秒检测一次，单位秒，Swoole会轮询所有TCP连接，将超过心跳时间的连接关闭掉
        'heartbeat_check_interval' => 30,
        // TCP连接的最大闲置时间，单位s , 如果某fd最后一次发包距离现在的时间超过
        'heartbeat_idle_time' => 60,
        // 在Server启动时自动将master进程的PID写入到文件，在Server关闭时自动删除PID文件
        'pid_file' => BASEDIR . '/log/tcp.pid',
        // 指定swoole错误日志文件
        'log_file' => BASEDIR . '/log/tcpSwoole.log',
//            'task_worker_num' => 8, //异步任务进程
//            "task_max_request" => 10,
    ],
    'websocket' => [
        // 在Server启动时自动将master进程的PID写入到文件，在Server关闭时自动删除PID文件
        'pid_file' => BASEDIR . '/log/websocket.pid',
        // 加入此参数后，执行php server.php将转入后台作为守护进程运行
        'daemonize' => env('daemonize') ? true : false,
        // 指定swoole错误日志文件
        'log_file' => BASEDIR . '/log/webSocketSwoole.log',
    ],
];