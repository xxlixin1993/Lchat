<?php
/**
 * ChatRoom.php
 * 描述
 * User: lixin
 * Date: 17-11-30
 */

namespace app\model;


use core\BaseModel;

class ChatRoom extends BaseModel
{
    protected $connection = 'chat';
    protected $table = 'room';
}