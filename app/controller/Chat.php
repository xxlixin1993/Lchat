<?php
/**
 * Chat.php
 * 聊天
 * User: lixin
 * Date: 17-11-30
 */

namespace app\controller;


use app\service\ChatService;
use core\WebSocketController;
use lib\Code;

class Chat extends WebSocketController
{
    /**
     * 创建聊天室
     * @param array $request
     * @author lixin
     */
    public function createRoom(array $request)
    {
        if (!isset($request['roomName'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $roomName = (string)$request['roomName'];
        $chatService = ChatService::getInstance();
        $result = $chatService->createRoom($roomName, $this->_frame->fd);
        if ($result) {
            $this->success([
                'router' => 'createChatRoom',
            ]);
        } else {
            $this->error(ChatService::CREATE_ROOM_ERROR, 'create room error');
        }
    }

    /**
     * 房间列表
     * @param array $request
     * @author lixin
     */
    public function roomList(array $request)
    {
        if (isset($request['page'])) {
            $page = (int)$request['page'];
        } else {
            $page = 1;
        }
        $chatService = ChatService::getInstance();
        $roomList = $chatService->roomList($page, 20);

        $this->success([
            'roomList' => $roomList,
            'router' => 'roomList',
        ]);
    }

    /**
     * 加入房间
     * @param array $request
     * @author lixin
     */
    public function joinRoom(array $request)
    {
        if (!isset($request['roomId'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $roomId = (int)$request['roomId'];
        $chatService = ChatService::getInstance();
        $result = $chatService->joinRoom($roomId, $this->_frame->fd);
        if ($result) {
            $this->success([
                'router' => 'addRoom',
            ]);
        } else {
            $this->error(ChatService::JOIN_ROOM_ERROR, 'join room error');
        }
    }

    /**
     * 退出房间
     * @param array $request
     * @author lixin
     */
    public function quitRoom(array $request)
    {
        if (!isset($request['roomId'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $roomId = (int)$request['roomId'];
        $chatService = ChatService::getInstance();
        $result = $chatService->quitRoom($roomId, $this->_frame->fd);
        if ($result) {
            $this->success([
                'router' => 'quitRoom',
            ]);
        } else {
            $this->error(ChatService::QUIT_ROOM_ERROR, 'quit room error');
        }
    }

    /**
     * 房间内聊天
     * @param array $request
     * @author lixin
     */
    public function roomChat(array $request)
    {
        if (!isset($request['roomId']) || !isset($request['msg'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $roomId = (int)$request['roomId'];
        $msg = (int)$request['msg'];
        $chatService = ChatService::getInstance();
        $fdRes = $chatService->roomChat($roomId);
        if ($fdRes) {
            foreach ($fdRes as $fd) {
                $this->success(
                    ['msg' => $msg, 'router' => 'roomChat'],
                    $fd
                );
            }
        } else {
            $this->error(ChatService::QUIT_ROOM_ERROR, 'room chat error');
        }
    }
}