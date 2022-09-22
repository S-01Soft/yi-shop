<?php

namespace app\shop\controller\index;

use app\shop\model\admin\ArticleModel;

class Article extends Base 
{
    public function index()
    {
        $id = $this->request->get('id');
        $article = ArticleModel::find($id);
        $config = get_module_config('shop');
        $this->assign('H5Url', $config['__DOMAIN__']);
        $this->assign('article', $article);
        return $this->fetch('shop/article');
    }
}