<?php
/**
 * TcpServer.php
 * TcpServer
 * User: lixin
 * Date: 17-8-4
 */

namespace core\socket;


use core\Config;
use core\LInstance;
use lib\CmdOutput;

class TcpServer implements ISocket
{
    /**
     * 链接id
     * @var array
     */
    public static $fds = [];


    /**
     * @var \swoole_server
     */
    private $_server;

    /**
     * TcpServer constructor.
     * @param string $host
     * @param int $port
     * @author lixin
     */
    public function __construct(string $host, int $port)
    {
        // TODO Socket的类型，支持TCP、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 6种，暂时只支持TCP
        $this->_server = new \swoole_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

        $this->_config();

        $this->_server->on('connect', function (\swoole_server $server, int $fd, int $fromId) {
            // 保存链接id
            self::$fds[$fromId] = $fd;
        });

        $this->_server->on('receive', function (\swoole_server $server, int $fd, int $fromId, string $data) {
            // TODO 分发
            if (!LInstance::getObjectInstance('router')->dispatchTcpAction($this, $fd, $fromId, $data)) {
                // 未找到路由
                $this->send($fd, 'Request fail, miss router');
            }
        });

        $this->_server->on('close', function (\swoole_server $server, int $fd, int $fromId) {
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
     * 配置TcpSocket
     * @author lixin
     */
    private function _config()
    {
        $config = Config::getInstance(BASEDIR);
        if (!empty($config['swoole']['tcp'])) {
            $this->_server->set($config['swoole']['tcp']);
        }
    }
}