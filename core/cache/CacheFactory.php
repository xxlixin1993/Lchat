<?php
/**
 * CacheFactory.php
 * 缓存工厂
 * User: lixin
 * Date: 17-5-20
 */

namespace core\cache;


use core\Config;

class CacheFactory
{
    ##STRING##############################################################
    // 反查 STRING_CHAT_USER_LOGIN_STATIC fd_{$fd} => $uid
    const STRING_CHAT_FD_LOGIN_STATIC = 'fd_';
    
    ##HASH################################################################
    // 聊天用户登陆状态 $uid => $fd 数据量大量可以用一致性hash等算法分开
    const HASH_CHAT_USER_LOGIN_STATIC = 'user';
    
    ##SET#################################################################
    // 房间内用户
    const SET_ROOM_USER = 'room_user';

    /**
     * cache object
     * @var null
     */
    public static $_handle = null;
    
    /**
     * 缓存工厂初始化
     * @param string $type 支持memcache|redis
     * @param array $config 缓存配置
     * @author lixin
     */
    public static function cacheFactory($type = 'memcache', array $config = [])
    {
        if ($type == 'memcache') {
            if (extension_loaded('memcached')) {
                self::$_handle['memcache'] = new Memcached($config);
            } else {
                self::$_handle['memcache'] = new Memcache($config);
            }
        } else if ($type == 'redis') {
            self::$_handle['redis'] = new Redis($config);
        }
    }
    
    public static function getRedis()
    {
        if (!isset(self::$_handle['redis'])) {
            self::cacheFactory('redis', Config::getInstance(BASEDIR)['redis']);
        }
        return self::$_handle['redis'];
    }
}