<?php
/**
 * BaseModel.php
 * 数据层
 * User: lixin
 * Date: 17-5-19
 */

namespace core;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}