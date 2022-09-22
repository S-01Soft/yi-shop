<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=文章管理,weigh=96000,ignore=tree|tree_list,ismenu=1)
 */
class Article extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ArticleValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\ArticleLogic::instance();
    }
}