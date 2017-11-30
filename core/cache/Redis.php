<?php
/**
 * Redis.php
 * 描述
 * User: lixin
 * Date: 17-5-20
 */

namespace core\cache;


use exception\FrameException;

class Redis
{
    /**
     * Redis链接句柄
     * @var array
     */
    private $_cacheHandle = null;

    /**
     * Redis链接服务器信息数组
     * @var array
     */
    private $_hostLists = array();

    /**
     * 构造函数
     * @param array $hostLists 链接的服务器信息 array("0" => array("host" => ,"port" => ,"weight" => ), "1" => array("host" => ,"port" => ,"weight" => )...)
     */
    public function __construct($hostLists)
    {
        if (!extension_loaded('redis')) {
            throw new FrameException('Failed to load redis extension');
        }

        if (count($hostLists) > 1) {
            //集群模式
            foreach ($hostLists as $value) {
                $this->_hostLists[] = $value['host'].':'.$value['port'];
            }

            $this->_cacheHandle = new \RedisCluster(null, $this->_hostLists);
        } elseif (count($hostLists) == 1) {
            //单机模式
            $this->_hostLists = array_shift($hostLists);
            $this->_cacheHandle = new \Redis();
            $this->_cacheHandle->connect($this->_hostLists['host'], $this->_hostLists['port']);
        }
        if (isset($this->_hostLists['password']) && !empty($this->_hostLists['password'])) {
            $this->_cacheHandle->auth($this->_hostLists['password']);
        }

        //设置序列化默认函数，兼容set函数对数组的支持
        if (extension_loaded('igbinary')) {
            $this->_cacheHandle->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
        } else {
            $this->_cacheHandle->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        }
    }

    /**
     * 设置缓存内容
     * @param  string  $itemKey     元素的key
     * @param  mixed   $itemValue   元素数据
     * @param  int     $expireTime 过期时间 <=0表示永远有效，单位秒
     * @return true
     */
    public function set($itemKey, $itemValue, $expireTime = 0)
    {
        //过期时间，0-2592000秒（30天），与memcache缓存服务等效设置，超过时间范围永久有效
        $expireTime = intval($expireTime);
        if ($expireTime < 0 || $expireTime > 2592000) {
            $expireTime = 0;
        }

        if ($expireTime > 0) {
            $this->_cacheHandle->set($itemKey, $itemValue, $expireTime);
        } else {
            $this->_cacheHandle->set($itemKey, $itemValue);
        }

        return true;
    }

    /**
     * 重载函数
     * @param string $name redis函数名
     * @param mixed $arguments 参数
     * @return mixed
     * @author lixin
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->_cacheHandle, $name), $arguments);
    }

    /**
     * 删除指定元素key的内容
     * @param  string $itemkey 元素的key
     * @return true
     */
    public function delete($itemkey)
    {
        $this->_cacheHandle->delete($itemkey);

        return true;
    }

    /**
     * 清空Redis所有数据
     * @return true
     */
    public function flush()
    {
        $this->_cacheHandle->flushDB();

        return true;
    }

    /**
     * 返回缓存服务器状态
     * @return false/array
     */
    public function getStats()
    {
        return $this->_cacheHandle->info();
    }
}