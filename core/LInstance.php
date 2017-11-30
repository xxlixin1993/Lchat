<?php
/**
 * LInstance.php
 * 单例类
 * @ex :
 * $cmdOutput = Load::getLib('\\lib\\CmdOutput');
 * $helpString = "Usage: php start.php -h HOST -p PORT -t TYPE";
 * echo $cmdOutput->getColoredString($helpString);
 * User: lixin
 * Date: 17-8-3
 */

namespace core;


class LInstance
{
    /**
     * @var array Load实例
     */
    private static $_instance;

    /**
     * 获取一个类的实例
     * @param string $libClassName 带命名空间的类名
     * @return mixed
     * @author lixin
     */
    public static function getClass(string $libClassName)
    {
        if (!isset(self::$_instance[$libClassName])) {
            self::$_instance[$libClassName] = new $libClassName();
        }
        return self::$_instance[$libClassName];
    }

    /**
     * 设置要存储的单例
     * @param string $key 名字
     * @param string $value 值
     * @return bool
     * @author lixin
     */
    public static function setStringInstance(string $key, string $value) : bool
    {
        return (self::$_instance[$key] = $value) ? true : false;
    }

    /**
     * 取出单例中存储的值
     * @param string $key 名字
     * @return string
     * @author lixin
     */
    public static function getStringInstance(string $key) : string
    {
        return isset(self::$_instance[$key]) ? self::$_instance[$key] : '';
    }

    /**
     * 设置要存储的对象单例
     * @param string $key 名字
     * @param ILInstance $value 值
     * @return bool
     * @author lixin
     */
    public static function setObjectInstance(string $key, ILInstance $value) : bool
    {
        return (self::$_instance[$key] = $value) ? true : false;
    }

    /**
     * 取出单例中存储的对象
     * @param string $key 名字
     * @return ILInstance
     * @author lixin
     */
    public static function getObjectInstance(string $key) : ILInstance
    {
        return isset(self::$_instance[$key]) ? self::$_instance[$key] : null;
    }
}