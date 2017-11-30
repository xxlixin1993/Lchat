<?php
/**
 * Code.php
 * 错误码
 * User: lixin
 * Date: 17-11-29
 */

namespace lib;


class Code
{
    // 成功响应
    const SUCCESS_RESPONSE = 1;

    // 参数错误 如请输出正确的参数
    const PARAMS_ERROR = 101;
    
    // 扩展错误 如“请先安装swoole扩展”
    const EXTENSION_ERROR = 102;
    
    // 权限错误 如“配置文件没有写权限”
    const PERMISSION_ERROR = 201;
    
    // 权限错误 如“配置文件没有写权限”
    const PROGRAM_ERROR = 301;
}