<?php
/**
 * start.php
 * 用法 :
 * 1 php start.php --help 查看帮助手册
 * 2 php start.php -h 127.0.0.1 -p 9501 -t websocket 启动一个webSocket服务
 * User: lixin
 * Date: 17-8-3
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/Init.php';

//定义根目录
define('BASEDIR', __DIR__);


try {
    \core\Init::getInstance()->run();
} catch (\exception\FrameException $fe) {
    // TODO error信息日志Handle
    \lib\CmdOutput::outputString($fe->getMessage());
}


