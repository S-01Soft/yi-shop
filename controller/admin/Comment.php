<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=Shop Comment,weigh=86000,ignore=add|edit|all|select|tree|tree_array|tree_list|imports|exports,ismenu=1)
 */
class Comment extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\CommentValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\CommentLogic::instance();
    }
}