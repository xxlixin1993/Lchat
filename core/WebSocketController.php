<?php
/**
 * WebSocketController.php
 * WebSocket服务控制器
 * User: lixin
 * Date: 17-11-24
 */

namespace core;


use core\cache\CacheFactory;
use core\socket\ISocket;
use lib\Code;

class WebSocketController extends BaseController
{
    /**
     * @var ISocket
     */
    protected $_socket;

    /**
     * @var \swoole_websocket_frame
     */
    protected $_frame;

    /**
     * WebSocketController constructor.
     * @param ISocket $socket
     * @param \swoole_websocket_frame $frame
     * @author lixin
     */
    public function __construct(ISocket $socket, \swoole_websocket_frame $frame)
    {
        // 可以封装一些WebSocket通用逻辑
        $this->_socket = $socket;
        $this->_frame = $frame;
    }

    /**
     * 打开链接需要处理的逻辑
     * @author lixin
     */
    public static function openDoing()
    {
        
    }

    /**
     * 关闭链接需要处理的逻辑
     * @param int $fd
     * @author lixin
     */
    public static function closeDoing(int $fd)
    {
    }

    /**
     * 成功返回客户端
     * @param array $data 数据
     * @return bool
     * @author lixin
     */
    public function success(array $data) : bool
    {
        return $this->_output(Code::SUCCESS_RESPONSE, 'ok', $data);
    }

    /**
     * 失败返回客户端
     * @param int $code 状态码
     * @param string $msg 信息
     * @return bool
     * @author lixin
     */
    public function error(int $code, string $msg) : bool
    {
        return $this->_output($code, $msg, []);
    }

    /**
     * 返回客户端
     * @param int $code 状态码
     * @param string $msg 信息
     * @param array $data 数据
     * @return bool
     * @author lixin
     */
    private function _output(int $code, string $msg, array $data) : bool
    {
        $sendData = [
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
        ];
        $result = json_encode($sendData);
        if (!$result) {
            $result = json_encode([
                'code' => Code::PARAMS_ERROR,
                'data' => $data,
                'msg' => $msg,
            ]);
        }
        return $this->_socket->send($this->_frame->fd, $result);
    }
}