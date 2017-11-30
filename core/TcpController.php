<?php
/**
 * TcpController.php
 * tcp服务控制器
 * User: lixin
 * Date: 17-11-24
 */

namespace core;


use core\socket\ISocket;

class TcpController extends BaseController
{
    /**
     * @var ISocket
     */
    protected $_socket;

    /**
     * @var int
     */
    protected $_fd;

    /**
     * TcpController constructor.
     * @param ISocket $socket
     * @param int $fd
     * @param int $fromId
     * @author lixin
     */
    public function __construct(ISocket $socket, int $fd, int $fromId)
    {
        // 可以封装一些Tcp通用逻辑
        $this->_socket = $socket;
        $this->_fd = $fd;
    }
}