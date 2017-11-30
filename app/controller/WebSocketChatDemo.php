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

    
    public function login(array $request)
    {
        $userInfo = CacheFactory::getRedis()->get($request['username']);

        if (!empty($userInfo) && $userInfo['password'] == $request['password']) {
            //登陆
            $data = [
                'username' => $request['username'],
                'router' => 'login',
                'status' => 1,
                'message' => 'login success'
            ];
        } else if (empty($userInfo)) {
            //第一次登陆 当做注册
            CacheFactory::getRedis()->set($request['username'], ['password' => $request['password']]);
            CacheFactory::getRedis()->set($this->_frame->fd, ['uid' => $this->_frame->fd, 'username' => $request['username']]);
            $data = [
                'username' => $request['username'],
                'router' => 'login',
                'status' => 1,
                'message' => 'ok'
            ];
        } else {
            $data = [
                'username' => $request['username'],
                'router' => 'login',
                'status' => 2,
                'message' => 'error'
            ];
        }

        $this->_socket->send($this->_frame->fd, json_encode($data));
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