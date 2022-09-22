<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=Shop Search,weigh=87000,ignore=all|select|tree|tree_array|tree_list|imports|exports,ismenu=1)
 */
class Search extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\SearchValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\SearchLogic::instance();
    }
}