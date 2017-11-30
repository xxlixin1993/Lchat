<?php
/**
 * Str.php
 * 描述
 * User: lixin
 * Date: 17-11-29
 */

namespace lib;


class Str
{
    /**
     * 生成随机码
     * @param int $length 长度
     * @param int $type 1数字 2小写字母 其他：数字+小写字母
     * @return string
     * @author lixin
     */
    public static function getRandom(int $length = 13, int $type = 0) : string
    {
        if ($type == 1) {
            $chars = '0123456789';
        } elseif ($type == 2) {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        } else {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        }
        
        $max = strlen($chars) - 1;
        mt_srand((double)microtime() * 1000000);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return (string)$hash;
    }
}