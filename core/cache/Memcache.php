<?php
/**
 * Memcache.php
 * memcache操作类
 * User: lixin
 * Date: 17-5-20
 */

namespace core\cache;


use exception\FrameException;

class Memcache
{
    /**
     * Memcache链接句柄
     * @var array
     */
    private $_cacheHandle = null;

    /**
     * Memcache链接服务器信息数组
     * @var array
     */
    private $_hostLists = array();

    /**
     * 构造函数（传输多个链接配置时，自动使用一致性hash方式）
     * @param array $hostLists 链接的服务器信息 array("0" => array("host" => ,"port" => ,"weight" => ), "1" => array("host" => ,"port" => ,"weight" => )...)
     */
    public function __construct($hostLists)
    {
        if (!extension_loaded('memcache')) {
            throw new FrameException('Failed to load memcache extension');
        }

        $this->_hostLists = $hostLists;

        //如果有多个的情况，就是用一致性hash
        if (is_array($this->_hostLists) && count($this->_hostLists) > 1) {
            //使用一致性hash
            ini_set('memcache.hash_strategy', 'consistent');
            //使用MurMur算法，还可以使用 Memcache::HASH_MD5 Memcache::HASH_CRC 算法等
            ini_set('memcache.hash_function', 'crc32');
        }

        $this->_cacheHandle = new \Memcache();

        //链接memcache
        foreach ($this->_hostLists as $value) {
            $this->_cacheHandle->addServer($value['host'], $value['port'], true, $value['weight']);
        }

    }

    /**
     * 设置缓存内容
     * @param  string  $itemKey     元素的key
     * @param  mixed   $itemValue   元素数据
     * @param  integer $expireTime 过期时间 <=0表示永远有效，单位秒
     * @return true
     */
    public function set($itemKey, $itemValue, $expireTime = 0)
    {
        //过期时间，0-2592000秒（30天），由于超过按照时间戳处理，这里处理为永久有效
        //参见 http://cn2.php.net/manual/zh/memcache.set.php
        $expireTime = intval($expireTime);
        if ($expireTime < 0 || $expireTime > 2592000) {
            $expireTime = 0;
        }

        $this->_cacheHandle->set($itemKey, $itemValue, false, $expireTime);


        return true;
    }

    /**
     * 获取指定元素key的内容
     * @param  string/array $keyList 元素的key
     * @return mixed
     */
    public function get($keyList)
    {
        if (is_string($keyList)) {
            return $this->_cacheHandle->get($keyList);
        } elseif (is_array($keyList)) {
            return $this->_cacheHandle->get($keyList);
        } else {
            return false;
        }
    }

    /**
     * 删除指定元素key的内容
     * @param  string $itemKey 元素的key
     * @return true
     */
    public function delete($itemKey)
    {
        $this->_cacheHandle->delete($itemKey);

        return true;
    }

    /**
     * 清空Memcache所有数据
     * @return true
     */
    public function flush()
    {
        $this->_cacheHandle->flush();

        return true;
    }

    /**
     * 返回缓存链接句炳
     * @return Memcache
     */
    public function getHandler()
    {
        return $this->_cacheHandle;
    }
}