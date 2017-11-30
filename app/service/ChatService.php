<?php
/**
 * ChatService.php
 * 聊天逻辑
 * User: lixin
 * Date: 17-11-29
 */

namespace app\service;


use core\BaseService;

class ChatService extends BaseService
{
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
    
    
}