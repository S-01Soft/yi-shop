<?php

namespace app\shop\model\admin;

class PageTemplateModel extends Model 
{
    protected $table = "shop_page_template";

    public $timestamps = false;

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = htmlspecialchars_decode($value);
    }

}