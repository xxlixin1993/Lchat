<?php
/**
 * WebSocket.php
 * WebSocket
 * User: lixin
 * Date: 17-8-3
 */

namespace core\socket;

use core\Config;
use core\LInstance;
use core\WebSocketController;
use lib\CmdOutput;

class WebSocket implements ISocket
{
    /**
     * @var \swoole_websocket_server
     */
    private $_server;
    

    /**
     * WebSocket constructor.
     * @param string $host
     * @param int $port
     * @author lixin
     */
    public function __construct(string $host, int $port)
    {
        $this->_server = new \swoole_websocket_server($host, $port);

        $this->_config();
        
        // 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
        $this->_server->on('open', function (\swoole_websocket_server $server, $request) {
            CmdOutput::outputString("client {$request->fd} connect");
            WebSocketController::openDoing();
        });

        // 当服务器收到来自客户端的数据帧时会回调此函数
        $this->_server->on('message', function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
            // client发送的数据
            if (!LInstance::getObjectInstance('router')->dispatchWebSocketAction($this, $frame)) {
                // 未找到路由
                $this->send($frame->fd, 'Request fail, miss router');
            }
        });

        // 当服务器收到来自客户端的关闭链接请求时会回调此函数
        $this->_server->on('close', function (\swoole_websocket_server $server, int $fd) {
            WebSocketController::closeDoing($fd);
            CmdOutput::outputString("client {$fd} closed");
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
    public function send(int $fd, string $msg) 
    {
        return $this->_server->push($fd, $msg);
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
        if (!empty($config['swoole']['websocket'])) {
            $this->_server->set($config['swoole']['websocket']);
        }
    }
}