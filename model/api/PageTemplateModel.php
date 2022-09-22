<?php

namespace app\shop\model\api;

class PageTemplateModel extends Model 
{
    protected $table = "shop_page_template";        

    public function getContentAttribute($value)
    {
        return empty($value) ? [] : (array)json_decode($value, true);
    }

}