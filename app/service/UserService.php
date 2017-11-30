<?php
/**
 * UserService.php
 * 用户逻辑
 * User: lixin
 * Date: 17-11-29
 */

namespace app\service;


use app\model\User;
use core\BaseService;
use core\cache\CacheFactory;
use lib\Str;

class UserService extends BaseService
{
    // 注册失败
    const SIGN_UP_ERROR = 1001;
    // 登陆失败
    const SIGN_IN_ERROR = 1002;
    // 登出失败
    const SIGN_OUT_ERROR = 1003;

    /**
     * @var UserService
     */
    protected static $_instance;

    /**
     * UserService constructor.
     * @author lixin
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取UserService单例
     * @return UserService
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
     * 注册
     * @param string $username 用户名
     * @param string $password 密码
     * @return int
     * @author lixin
     */
    public function signUp(string $username, string $password) : int
    {
        $salt = Str::getRandom(6);
        $userModel = new User();
        try {
            $insertId = $userModel->insertGetId([
                'username' => $username,
                'password' => md5($password . $salt),
                'salt' => $salt,
                'createTime' => time()
            ]);
            return $insertId;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 登陆
     * @param string $username
     * @param string $password
     * @param int $fd
     * @return bool
     * @author lixin
     */
    public function signIn(string $username, string $password, int $fd) : bool
    {
        $userModel = new User();
        $userInfo = $userModel->where('username', $username)->get()->toArray();
        if (!$userInfo) {
            return false;
        }

        if ($userInfo[0]['password'] == md5($password . $userInfo[0]['salt'])) {
            // 有登陆直接挤掉之前的
            CacheFactory::getRedis()->hset(CacheFactory::HASH_CHAT_USER_LOGIN_STATIC, $userInfo[0]['uid'], $fd);
            CacheFactory::getRedis()->set(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd, $userInfo[0]['uid']);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 登出
     * @param int $fd
     * @author lixin
     */
    public function signOut(int $fd)
    {
        $uid = CacheFactory::getRedis()->get(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd);
        // 清除登陆状态
        CacheFactory::getRedis()->hdel(CacheFactory::HASH_CHAT_USER_LOGIN_STATIC, $uid);
        CacheFactory::getRedis()->del(CacheFactory::STRING_CHAT_FD_LOGIN_STATIC . $fd);
    }
}