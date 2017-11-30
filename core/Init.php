<?php
/**
 * Init.php
 * 初始化
 * User: lixin
 * Date: 17-8-3
 */

namespace core;


use Dotenv\Dotenv;
use exception\FrameException;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use lib\Code;

class Init
{
    /**
     * @var Init init实例
     */
    protected static $_instance;

    /**
     * Init constructor.
     */
    protected function __construct()
    {
        //注册自动加载函数
        spl_autoload_register('\\core\\Init::autoload');
    }

    /**
     * 类的自动加载
     * @param string $class 带命名空间的类名
     * @author lixin
     */
    public static function autoload(string $class)
    {
        if (file_exists(BASEDIR . '/' . str_replace('\\', '/', $class) . '.php')) {
            include BASEDIR . '/' . str_replace('\\', '/', $class) . '.php';
        }
    }

    /**
     * 单例获得一个init实例
     * @return Init
     * @author lixin
     */
    public static function getInstance() : Init
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 初始化程序
     * @throws FrameException
     * @author lixin
     */
    public function run()
    {
        if (!extension_loaded('swoole')) {
            throw new FrameException('Plz install swoole first', Code::EXTENSION_ERROR);
        }

        // 获取命令行参数
        $option = getopt("h:p:t:", ["help"]);
        if (!$option) {
            throw new FrameException('Plz check you input param, you can use --help to read menu', Code::PARAMS_ERROR);
        }

        // 检查输入参数是否符合规范
        if (!$this->checkCmdParam($option)) {
            throw new FrameException('Plz check you input param, you can use --help to read menu', Code::PARAMS_ERROR);
        }

        if (isset($option['help'])) {
            return true;
        }

        // 初始化env
        $dotEnv = new Dotenv(BASEDIR);
        $dotEnv->load();

        // 配置框架
        $this->_config();

        // 初始化数据库
        $this->_connMysql();

        // 分发命令行参数
        $router = new Router();

        if (!LInstance::setObjectInstance('router', $router)) {
            throw new FrameException('Init router fail', Code::PROGRAM_ERROR);
        }
        $router->dispatchOption();
    }

    /**
     * 检查输入参数
     * @param array $option getopt("h:p:t:", ["help"]); 的返回值
     * @return bool
     * @throws FrameException
     * @author lixin
     */
    private function checkCmdParam(array $option) : bool
    {
        $helpString = "Usage:   php start.php -h HOST -p PORT -t TYPE \n\n";
        $helpString .= "-h \t HOST \t Server hostname (ex: 127.0.0.1).\n";
        $helpString .= "-p \t PORT \t Server port (ex: 9501).\n";
        $helpString .= "-t \t TYPE \t Socket type (ex: websocket), support[websocket, tcp, http].\n";

        if (isset($option['help'])) {
            echo $helpString;
            return true;
        }

        if (isset($option['h']) && isset($option['p']) && isset($option['t'])) {
            if (LInstance::setStringInstance('h', $option['h'])
                && LInstance::setStringInstance('p', $option['p'])
                && LInstance::setStringInstance('t', $option['t'])
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 初始化数据库
     * @author lixin
     */
    private function _connMysql()
    {
        $capsule = new Capsule;
        $config = Config::getInstance(BASEDIR);
        foreach ($config['database'] as $key => $value) {
            $capsule->addConnection($value, $key);
        }

        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    /**
     * 框架配置
     * @author lixin
     */
    private function _config()
    {
        if (env('DEBUG')) {
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }
    }
}