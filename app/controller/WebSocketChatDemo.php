<?php
/**
 * WebSocketChatDemo.php
 * websocket聊天demo
 * User: lixin
 * Date: 17-11-24
 */

namespace app\controller;


use core\cache\CacheFactory;
use core\WebSocketController;

class WebSocketChatDemo extends WebSocketController
{
    /**
     * 测试函数
     * @param array $request
     * @author lixin
     */
    public function test(array $request)
    {
        $this->_socket->send($this->_frame->fd, 'hello world');
    }
    
    public function chatRoom(array $request)
    {
        $data = [
            'username' => $this->_frame->fd,
            'router' => 'chatRoom',
            'status' => 1,
            'msg' => $request['msg']
        ];
        foreach ($this->_socket->getConnections() as $fd) {
            $this->_socket->send($fd, json_encode($data));
        }

    }
}