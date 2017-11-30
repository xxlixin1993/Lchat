<?php
/**
 * Created by PhpStorm.
 * User: lixin
 * Date: 2017/3/26
 * Time: 下午1:42
 */
namespace core;

use exception\FrameException;
use lib\Code;

/**
 * 加载配置文件
 */
class Config implements \ArrayAccess
{
    /**
     * 将所有的配置存放到自己的私有变量
     * @var array
     */
    private $config = array();

    /**
     * 保存要获取的config文件的路径
     * @var string
     */
    private $path;

    /**
     * @var Config
     */
    private static $_instance;

    /**
     * Config constructor.
     * @param $path
     */
    private function __construct($path)
    {
        //组织配置文件路径
        $this->path = $path . '/config/';
    }

    /**
     * 获取实例
     * @param string $path
     * @return Config
     * @author lixin
     */
    public static function getInstance(string $path) : Config
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self($path);
        }
        return self::$_instance;
    }

    /**
     * 实现ArrayAccess的4个方法
     * @param mixed $key 配置文件名
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (!isset($config[$key])) {
            //把配置文件的数组放在config属性中
            $this->config[$key] = require $this->path . $key . '.php';
        }
        //返回所调用的配置文件名
        return $this->config[$key];
    }

    /**
     * 当要设置数组时会调用
     * @param mixed $key
     * @param mixed $value
     * @throws FrameException
     * @author lixin
     */
    public function offsetSet($key, $value)
    {
        throw new FrameException("Cannot write config file.Plz modify the configuration by writing profiles.", Code::PERMISSION_ERROR);

    }

    /**
     * 当调用isset()时会调用
     * @param mixed $key
     * @return bool
     * @author lixin
     */
    public function offsetExists($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * 当要清除数组时会调用
     * @param mixed $key
     * @throws FrameException
     * @author lixin
     */
    public function offsetUnset($key)
    {
        throw new FrameException("Cannot write config file.Plz modify the configuration by writing profiles.", Code::PERMISSION_ERROR);
    }
}