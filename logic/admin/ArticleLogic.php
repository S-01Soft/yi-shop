<?php

namespace app\shop\logic\admin;

use app\shop\model\admin\ArticleCategoryModel;
use support\Db;

class ArticleLogic extends Logic
{
    protected $where_ignore = ['categories'];

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ArticleModel::class;
        parent::initialize();
    }

    protected function beforePaginate($query)
    {
        $query->with(['categories']);
        $c = (array)json_decode(request()->get('where', null, ['']), true);
        if (!empty($c['categories'][1])) {

            $query->whereIn('id', function($q) use ($c) {
                $q->from('shop_articles_categories')->whereIn('category_id', $c['categories'][1])->select('article_id');
            });
        }
    }

    protected function beforePostAdd($form)
    {
        unset($form['categories']);
        return $form;
    }

    protected function afterPostAdd($model, $form = [])
    {        
        $form = request()->post('form');
        if (!empty($form['categories'])) {
            $ids = explode(',', $form['categories']);
            $data = [];
            foreach ($ids as $id) {
                $data[] = [
                    'category_id' => $id,
                    'article_id' => $model->id
                ];
            }
            Db::table('shop_articles_categories')->insert($data);
        }
        return $model;
    }

    protected function beforePostEdit($form, $query)
    {
        unset($form['categories']);
        return $form;
    }
    
    protected function afterGetEdit($model)
    {
        $ids = $model->categories()->pluck('category_id')->toArray();
        $model->categories = implode(',', $ids);
        return $model;
    }

    protected function afterPostEdit($model, $form = [])
    {
        $form = request()->post('form');
        Db::table('shop_articles_categories')->where('article_id', $model->id)->delete();
        if (!empty($form['categories'])) {
            $ids = explode(',', $form['categories']);
            $data = [];
            foreach ($ids as $id) {
                $data[] = [
                    'category_id' => $id,
                    'article_id' => $model->id
                ];
            }
            Db::table('shop_articles_categories')->insert($data);
        }
        return $model;
    }
    
}