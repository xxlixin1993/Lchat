<?php

/**
 * HttpServer.php
 * HttpServer
 * User: lixin
 * Date: 17-8-4
 */
namespace core\socket;

use core\Config;
use core\LInstance;
use exception\FrameException;
use lib\CmdOutput;
use lib\Log;

class HttpServer implements ISocket
{
    /**
     * @var \swoole_http_server
     */
    private $_server;

    /**
     * HttpServer constructor.
     * @param string $host
     * @param int $port
     * @author lixin
     */
    public function __construct(string $host, int $port)
    {
        $this->_server = new \swoole_http_server($host, $port);

        $this->_config();

        // 在收到一个完整的http请求后，会回调此函数
        $this->_server->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            // 记录http请求 相当于nginx的access_log
            $log = Log::getLogHandler('AccessLog');
            $log->info('Request fail, miss controller or action. Param:', [
                'uri' => $request->server['request_uri'],
                'request_method' => $request->server['request_method'],
            ]);
            $response->header("Content-Type", "text/html; charset=utf-8");
            LInstance::getObjectInstance('router')->dispatchHttpAction($request, $response);
        });

        CmdOutput::outputString("Type: " . LInstance::getStringInstance('t') . "\t Listen: " . $host . ':' . $port);
    }

    /**
     * 发送消息
     * @param int $fd
     * @param string $msg
     * @return bool
     * @author lixin
     */
    public function send(int $fd, string $msg) : bool
    {
        return $this->_server->send($fd, $msg);
    }

    /**
     * 关闭链接
     * @param int $fd
     * @return bool
     * @author lixin
     */
    public function close(int $fd) : bool
    {
        return $this->_server->close($fd);
    }

    /**
     * 开始监听
     * @return bool
     * @author lixin
     */
    public function start() : bool
    {
        return $this->_server->start();
    }

    /**
     * 返回所有链接
     * @return mixed
     * @author lixin
     */
    public function getConnections()
    {
        return $this->_server->connections;
    }

    /**
     * 配置WebSocket
     * @author lixin
     */
    private function _config()
    {
        $config = Config::getInstance(BASEDIR);
        if (!empty($config['swoole']['http'])) {
            $this->_server->set($config['swoole']['http']);
        }
    }
}