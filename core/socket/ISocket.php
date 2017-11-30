<?php

/**
 * ISocket.php
 * socket接口
 * User: lixin
 * Date: 17-8-4
 */
namespace core\socket;

interface  ISocket
{
    /**
     * 发送消息
     * @param int $fd 链接
     * @param string $msg 信息
     * @return bool
     * @author lixin
     */
    public function send(int $fd,string $msg);

    /**
     * 关闭链接
     * @param int $fd 链接
     * @return bool
     * @author lixin
     */
    public function close(int $fd);

    /**
     * 启动服务 开始监听
     * @return bool
     * @author lixin
     */
    public function start();

    /**
     * 返回所有链接
     * @return mixed
     * @author lixin
     */
    public function getConnections();
}