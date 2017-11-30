<?php
/**
 * ParseParam.php
 * 解析参数
 * User: lixin
 * Date: 17-8-3
 */

namespace lib;


class ParseParam
{
    /**
     * 校验ip是否正确
     * @param string $ip
     * @return bool
     * @author lixin
     */
    public static function isIp(string $ip) : bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }
}