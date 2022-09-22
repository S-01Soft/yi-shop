<?php

namespace app\shop\model\admin;

class PageGroupModel extends Model 
{
    protected $table = "shop_page_group";

    protected $createTime = 'created_at';
    const UPDATED_AT = NULL;

    public function getContentAttribute($value)
    {
        return empty($value) ? (object)[] : json_decode($value);
    }

    public function setContentAttribute($value)
    {
        if (is_array($value)) $this->attributes['content'] = is_array($value) ? json_encode($value) : $value;
    }

}