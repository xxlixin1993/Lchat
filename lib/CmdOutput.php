<?php
/**
 * CmdOutput.php
 * cmd输出修饰
 * \r\n 换行  \n 换行 linux
 * \t 跳格
 * User: lixin
 * Date: 17-8-3
 */

namespace lib;


class CmdOutput
{
    /**
     * 字体色
     * @var array
     */
    private $foreground_colors = array();

    /**
     * 背景色
     * @var array
     */
    private $background_colors = array();

    public function __construct()
    {
        // 设置shell命令行的颜色
        $this->foreground_colors = [
            'black' => '0;30',
            'dark_gray' => '1;30',
            'blue' => '0;34',
            'light_blue' => '1;34',
            'green' => '0;32',
            'light_green' => '1;32',
            'cyan' => '0;36',
            'light_cyan' => '1;36',
            'red' => '0;31',
            'light_red' => '1;31',
            'purple' => '0;35',
            'light_purple' => '1;35',
            'brown' => '0;33',
            'yellow' => '1;33',
            'light_gray' => '0;37',
            'white' => '1;37',
        ];

        $this->background_colors = [
            'black' => '40',
            'red' => '41',
            'green' => '42',
            'yellow' => '43',
            'blue' => '44',
            'magenta' => '45',
            'cyan' => '46',
            'light_gray' => '47',
        ];

    }

    /**
     * 获取有颜色的CMD文字
     * @param string $string 要修饰的文字
     * @param string $fgColor 前景色
     * @param string $bgColor 背景色
     * @return string
     * @author lixin
     */
    public function getColoredString(string $string, string $fgColor = '', string $bgColor = '') : string
    {
        $colored_string = "";

        if (isset($this->foreground_colors[$fgColor])) {
            $colored_string .= "\033[" . $this->foreground_colors[$fgColor] . "m";
        }
        if (isset($this->background_colors[$bgColor])) {
            $colored_string .= "\033[" . $this->background_colors[$bgColor] . "m";
        }

        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    /**
     * 获取所有前景色
     * @return array
     * @author lixin
     */
    public function getForegroundColors()
    {
        return array_keys($this->foreground_colors);
    }

    /**
     * 获取所有背景色
     * @return array
     * @author lixin
     */
    public function getBackgroundColors()
    {
        return array_keys($this->background_colors);
    }

    /**
     * 输出
     * @param string $string
     * @author lixin
     */
    public static function outputString(string $string = '')
    {
        $date = date("Y-m-d H:i:s");
        echo "[{$date}] " . $string . "\n";
    }

}