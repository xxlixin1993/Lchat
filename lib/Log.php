<?php
/**
 * Log.php
 * 日志类
 * User: lixin
 * Date: 17-5-19
 * Monolog文档:https://github.com/Seldaek/monolog/blob/HEAD/doc/01-usage.md
 * example:
 * $log = Log::getLogHandler('Router');
 * $log->warning('Request fail, miss controller or action. Param:',[
 *     'uri'=>$request->server['request_uri'],
 *     'request_method'=>$request->server['request_method'],
 * ]);
 */

namespace lib;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{

    /**
     * @var Logger
     */
    public $_logger;

    /**
     * 日志写文件
     */
    const FILE = 1;
    
    /**
     * 日志写文件
     * @param string $logName 日志中显示的名字
     * @param string $logFileName 日志文件名
     * @param int $level 日志级别
     * @param int $type 处理日志类型 根据Log类的常量来做判断
     * @return Logger
     * @author lixin
     */
    public static function getLogHandler($logName, $logFileName = 'frame', $level = Logger::INFO, $type= self::FILE)
    {
        $logger = new Logger($logName);

        if ($type == self::FILE) {
            $logger->pushHandler(new StreamHandler(BASEDIR . '/log/'. $logFileName . '.log', $level));
        }
        
        return $logger;
    }
}