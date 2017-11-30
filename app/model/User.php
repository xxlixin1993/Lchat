<?php
/**
 * User.php
 * 描述
 * User: lixin
 * Date: 17-11-29
 */

namespace app\model;


use core\BaseModel;

class User extends BaseModel
{
    protected $connection = 'chat';
    protected $table = 'user';
}