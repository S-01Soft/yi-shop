<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=文章分类管理,weigh=97000,ismenu=1)
 */
class ArticleCategory extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ArticleCategoryValidate::class;
    
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\ArticleCategoryLogic;
    }
}