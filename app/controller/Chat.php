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
    public function createChatRoom(array $request)
    {
        if (!isset($request['roomName'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $roomName = (string)$request['roomName'];
        $chatService = ChatService::getInstance();
        $result = $chatService->createRoom($roomName, $this->_frame->fd);
        if ($result) {
            $this->success([
                'roomName' => $roomName,
                'router' => 'createChatRoom',
            ]);
        } else {
            $this->error(ChatService::CREATE_ROOM_ERROR, 'create room error');
        }
    }
}