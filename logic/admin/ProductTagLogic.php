<?php

namespace app\shop\logic\admin;

use app\shop\model\admin\TagProductsModel;
use app\shop\model\admin\ProductModel;

class ProductTagLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ProductTagModel::class;
        parent::initialize();
    }

    public function paginateView($c) 
    {
        $c->hidden = ['import_btn', 'export_btn'];
    }

    protected function beforeGetEdit($query)
    {
        $query->with(['products']);
    }

    protected function beforePostAdd($form)
    {
        unset($form['products']);
        return $form;
    }

    protected function afterPostAdd($model, $form = [])
    {
        $ids = request()->post('form')['products'] ?? [];
        $this->saveTagProducts($model->id, $ids);
        return $model;
    }

    protected function beforePostEdit($form, $query)
    {
        unset($form['products']);
        return $form;
    }
    protected function afterPostEdit($model, $form = [])
    {
        $ids = request()->post('form')['products'] ?? [];
        $this->saveTagProducts($model->id, $ids);
        return $model;
    }

    protected function saveTagProducts($tag_id, $ids)
    {
        TagProductsModel::where('tag_id', $tag_id)->delete();
        if (!empty($ids)) {
            foreach (ProductModel::whereIn('id', $ids)->get() as $row) {
                ev('ShopProductAfterUpdate', $row);
            }
            $list = [];
            foreach ($ids as $id) {
                $list[] = ['tag_id' => $tag_id, 'product_id' => $id];
            }
            $model = new TagProductsModel;
            $model->insert($list);
        }
    }

    
}