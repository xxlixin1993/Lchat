<?php
/**
 * User.php
 * 用户
 * User: lixin
 * Date: 17-11-29
 */

namespace app\controller;


use app\service\UserService;
use core\WebSocketController;
use lib\Code;

class User extends WebSocketController
{
    /**
     * 注册
     * @param array $request
     * @author lixin
     */
    public function signUp(array $request)
    {
        if (!isset($request['username']) || !isset($request['password'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $username = (string)$request['username'];
        $password = (string)$request['password'];

        $userService = UserService::getInstance();
        $result = $userService->signUp($username, $password);
        if ($result) {
            $this->success([
                'router' => 'signUp',
            ]);
        } else {
            $this->error(UserService::SIGN_UP_ERROR, 'sign up error');
        }
    }

    /**
     * 登陆
     * @param array $request
     * @author lixin
     */
    public function signIn(array $request)
    {
        if (!isset($request['username']) || !isset($request['password'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $username = (string)$request['username'];
        $password = (string)$request['password'];

        $userService = UserService::getInstance();
        $result = $userService->signIn($username, $password, $this->_frame->fd);
        if ($result) {
            $this->success([
                'username' => $username,
                'router' => 'signIn',
            ]);
        } else {
            $this->error(UserService::SIGN_IN_ERROR, 'sign in error');
        }
    }

    /**
     * 登出
     * @param array $request
     * @author lixin
     */
    public function signOut(array $request)
    {
        if (!isset($request['username'])) {
            $this->error(Code::PARAMS_ERROR, 'params error');
        }
        $username = (string)$request['username'];
        $userService = UserService::getInstance();
        $result = $userService->signOut($username, $this->_frame->fd);
        if ($result) {
            $this->success([
                'router' => 'signOut',
            ]);
        } else {
            $this->error(UserService::SIGN_OUT_ERROR, 'sign out error');
        }
    }
}