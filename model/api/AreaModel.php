<?php

namespace app\shop\model\api;

class AreaModel extends Model
{
    protected $table = 'area';
    protected $visible = [
        'id', 'pid', 'shortname', 'mergename', 'name', 'level', 'first'
    ];
}
