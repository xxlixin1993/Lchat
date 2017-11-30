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
    // 加入房间失败
    const JOIN_ROOM_ERROR = '2002';
    // 退出房间失败
    const QUIT_ROOM_ERROR = '2003';
    // 房间聊天失败
    const ROOM_CHAT_ERROR = '2004';
    
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
    public static function getInstance() : ChatService
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

        $insertId = $chatRoomModel->insertGetId([
            'roomName' => $roomName,
            'roomOwnerUid' => $uid,
            'createTime' => time()
        ]);
        if ($insertId) {
            CacheFactory::getRedis()->sadd(CacheFactory::SET_ROOM_USER . $insertId, $uid);
            return $insertId;
        } else {
            return 0;
        }
    }

    /**
     * 房间列表
     * @param int $page
     * @param int $pageSize
     * @return array
     * @author lixin
     */
    public function roomList(int $page, int $pageSize) : array
    {
        $result = ChatRoom::paginate($pageSize, ['*'], 'page', $page)->toArray();
        if (isset($result['data'][0])) {
            return $result['data'];
        } else {
            return [];
        }
    }

    /**
     * 加入房间
     * @param int $roomId
     * @param int $fd
     * @return int
     * @author lixin
     */
    public function joinRoom(int $roomId, int $fd) : int
    {
        $uid = CacheFactory::getRedis()->get(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd);
        if ($uid) {
            return CacheFactory::getRedis()->sadd(CacheFactory::SET_ROOM_USER . $roomId, $uid);
        } else {
            return 0;
        }
    }

    /**
     * 退出房间
     * @param int $roomId
     * @param int $fd
     * @return int
     * @author lixin
     */
    public function quitRoom(int $roomId, int $fd) : int
    {
        $uid = CacheFactory::getRedis()->get(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd);
        if ($uid) {
            return CacheFactory::getRedis()->srem(CacheFactory::SET_ROOM_USER . $roomId, $uid);
        } else {
            return 0;
        }
    }

    public function roomChat(int $roomId) : array
    {

    }
}