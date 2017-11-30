<?php
/**
 * ChatService.php
 * 聊天逻辑
 * User: lixin
 * Date: 17-11-29
 */

namespace app\service;


use app\model\ChatRoom;
use core\BaseService;
use core\cache\CacheFactory;

class ChatService extends BaseService
{
    // 创建房间失败
    const CREATE_ROOM_ERROR = '2001';
    /**
     * @var ChatService
     */
    protected static $_instance;
    
    /**
     * ChatService constructor.
     * @author lixin
     */
    protected function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 获取ChatService单例
     * @return ChatService
     * @author lixin
     */
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 创建房间
     * @param string $roomName
     * @param int $fd
     * @return int
     * @author lixin
     */
    public function createRoom(string $roomName, int $fd) : int
    {
        $uid = CacheFactory::getRedis()->get(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd);
        if (!$uid) {
            return 0;
        }
        $chatRoomModel = new ChatRoom();
        try {
            $insertId = $chatRoomModel->insertGetId([
                'roomName' => $roomName,
                'roomOwnerUid' => $uid,
                'createTime' => time()
            ]);
            
            return $insertId;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
}