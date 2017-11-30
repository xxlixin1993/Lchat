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
    public function addRoom(array $request)
    {

    }

    /**
     * 房间内聊天
     * @param array $request
     * @author lixin
     */
    public function roomChat(array $request)
    {

    }
}