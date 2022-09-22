<?php

namespace app\shop\logic\admin;

use yi\Validate;
use support\exception\Exception;
use app\shop\model\admin\ProductSkuModel;
use app\shop\model\admin\ProductServiceModel;
use yi\Auth;

class ProductLogic extends Logic
{
    protected $toggleFields = ['status', 'on_sale'];

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ProductModel::class;
        parent::initialize();
        $this->auth = get_admin();
    }

    public function paginateView($c)
    {
        $c->hidden = ['import_btn', 'export_btn'];
    }

    protected function beforePaginate($query)
    {
        $query->with(['category','unit','deliveryTpl', 'createUserC', 'skus']);
    }
    protected function afterPaginate($result)
    {
        foreach($result as $row) {
            if($row->category) $row->category->setVisible(['id', 'name']);
            if($row->delivery_tpl) $row->delivery_tpl->setVisible(['id', 'title']);
        }
        return $result;
    }
    protected function beforeSelect($query)
    {
        $query->with(['category','unit','deliveryTpl']);
    }
    protected function afterSelect($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','title','description','image','on_sale','sold_count','comment_count','created_at','updated_at','score_persent','auto_receive_time','category','unit','delivery_tpl']);
            if($row->category) $row->category->setVisible(['id', 'name']);
            if($row->delivery_tpl) $row->delivery_tpl->setVisible(['id', 'title']);
        }
        return $result;
    }
    protected function beforeAll($query)
    {
        $query->with(['category','unit','deliveryTpl']);
    }
    protected function afterAll($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','title','description','image','on_sale','sold_count','comment_count','created_at','updated_at','score_persent','auto_receive_time','category','unit','delivery_tpl']);
            if($row->category) $row->category->setVisible(['id', 'name']);
            if($row->delivery_tpl) $row->delivery_tpl->setVisible(['id', 'title']);
        }
        return $result;
    }
    

    protected function beforePostAdd($form)
    {
        unset($form['skus']);
        $form['create_user'] = $this->auth->id;
        return $form;
    }

    protected function afterPostAdd($model, $form = [])
    {
        $form = request()->post('form');
        $skus = $form['skus'];
        $this->updateServices($model->id, $form['service_tags']);
        return $this->createOrUpdate($model, $skus, 1);
    }

    public function editView($controller)
    {
        $id = request()->get('id');
        $row = $this->static::with(['skus' => function($q) {
            $q->orderBy('sort', 'asc');
        }])->find($id);
        $attrItems = [];
        if (!empty($row->skus)) {
            $result = [];
            foreach ($row->skus as $i => $item) {
                $result[] = explode(',', $item->value);
            }
            $attrItems = reverse_descartes($result);
        }
        get_view()::assign('attrItems', json_encode($attrItems, JSON_UNESCAPED_UNICODE));
    }

    protected function beforeGetEdit($query)
    {
        $query->with(['skus' => function($q) {
            $q->orderBy('sort', 'asc');
        }]);
    }

    protected function beforePostEdit($form, $query) 
    {
        $query->with(['skus']);
        unset($form['skus']);
        $form['create_user'] = $this->auth->id;
        return $form;
    }

    protected function afterPostEdit($row, $form = [])
    {
        $form = request()->post('form');
        $skus = $form['skus'];
        $this->updateServices($row->id, $form['service_tags']);
        return $this->createOrUpdate($row, $skus, 2);
    }

    protected function updateServices($product_id, $service_ids)
    {
        if (empty($service_ids)) return;
        $ids = explode(',', $service_ids);
        ProductServiceModel::where('product_id', $product_id)->delete();
        foreach ($ids as $id) {
            $list[] = [
                'product_id' => $product_id,
                'service_id' => $id
            ];
        }
        ProductServiceModel::insert($list);
    }

    protected function createOrUpdate($product, $skus, $type = 1)
    {
        $result = [
            'update' => [],
            'insert' => 0,
            'delete' => 0
        ];
        $old_skus = $type === 1 ? [] : $product->toArray()['skus'];
        $list = [];
        $sku_ids = [];
        foreach ($skus as $i => $sku) {
            $keys = $sku['keys'];
            $sku['product_id'] = $product['id'];
            if ($sku['stock'] <= 0) {
                $rule = [
                    'product_id|商品' => 'require|number',
                    'keys' => 'require',
                    'value' => 'require',
                    'market_price|市场价' => 'require|number|>=:0',
                    'price|销售价' => 'require|number|>=:0',
                    'stock|库存' => 'require|integer|>=:0',
                    'weight|重量' => 'require|number|>=:0'
                ];
            } else {
                $rule = [
                    'product_id|商品' => 'require|number',
                    'keys' => 'require',
                    'value' => 'require',
                    'market_price|市场价' => 'require|number|>:0',
                    'price|销售价' => 'require|number|>:0',
                    'stock|库存' => 'require|integer|>:0',
                    'weight|重量' => 'require|number|>:0'
                ];
            }
            $validate = new Validate($rule);
            if (!$validate->check($sku)) {
                throw new Exception($validate->getError());
            }
            if ($sku['stock'] > 0) {
                $skus_prices[] = $sku['price'];
            }
            $index = find_rows($old_skus, ['keys' => $keys, 'value' => $sku['value'], 'product_id' => $product->id]);

            if ($index > -1 && isset($sku['id'])) {
                if (!isset($sku['id'])) $sku['id'] = $old_skus[$index]['id'];
                ProductSkuModel::where('id', $sku['id'])->update($sku);
                $list[] = array_merge($old_skus[$index], $sku);
                $result['update'][] = $old_skus[$index]['id'];
                $sku_ids[] = $sku['id'];
            } else {
                $_sku = ProductSkuModel::create($sku);
                $list[] = $sku;
                $result['insert'] += 1;
                $sku_ids[] = $_sku['id'];
            }
        }
        // 删除旧数据
        ProductSkuModel::where('product_id', $product->id)->whereNotIn('id', $sku_ids)->delete();
        $product->price = isset($skus_prices) ? min($skus_prices) : 0;
        $payload = (object)[
            'product' => $product,
            'result' => $result,
            'type' => $type
        ];
        event('CreateOrUpdateProduct', $payload);
        $payload->product->save();
        return $payload->result;
    }

}