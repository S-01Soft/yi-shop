<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use app\shop\model\api\TagProductsModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\logic\api\CategoryLogic;
use yi\User;

class ProductLogic extends Logic 
{
    protected function initialize()
    {
        $this->static = \app\shop\model\api\ProductModel::class;
        parent::initialize();
    }

    public function info($id)
    {
        $user = get_user()->getUser();
        $product = $this->static::with(['skus', 'services', 'unit', 'favorite' => function ($query) use ($user) {
            $user_id = empty($user) ? -1 : $user->id;
            return $query->where('user_id', $user_id);
        }])->find($id);
        if (empty($product)) {
            throw new Exception("商品不存在");
        }
        $product = $product->decars();
        $product->favorite = empty($product->favorite) ? 0 : 1;
        $payload = (object)[
            'user' => $user,
            'product' => $product
        ];
        event('ShopGetProductInfo', $payload);
        return $payload->product;
    }

    public function sku($id)
    {
        $sku = ProductSkuModel::with(['product'])->where('id', $id)->first();
        return $sku;
    }

    /**
     * 获取商品列表
     */
    public function getList($attributes)
    {
        extract($attributes);
        $per_page = empty($per_page) ? 10 : intval($per_page);
        $per_page = min($per_page, 50);
        $query = $this->static::with(['skus' => function($q) {
            $q->orderBy('sort', 'asc');
        }, 'unit'])->where('on_sale', 1);
        if (!empty($tag_id)) {
            $ids = TagProductsModel::where('tag_id', $tag_id)->pluck('product_id')->toArray(); 
            $query->whereIn('id', $ids);
        }
        if (!empty($category_id)) {
            $query->where(function ($query) use ($category_id) {
                $categoryLogic = CategoryLogic::instance();
                $query->whereIn('category_id', array_column($categoryLogic->getChildren($category_id, true), 'id'));
            });
        }
        if (!empty($kw)) {
            $query->where(function ($q) use ($kw) {
                $q->orWhere('title', 'like', "%$kw%")->orWhere('description', 'like', "%$kw%");
            });
        }
        if (!isset($sort)) {
            $sort = 0;
        }
        $sort = intval($sort);
        switch ($sort) {
            case 0: {
                $query->orderBy('category_recommend', 'DESC')->orderBy('updated_at', 'DESC');
            }
            case 1: {
                $query->orderBy('sold_count', 'DESC');
                break;
            }
            case 2: {
                if ($price_order == 1) {
                    $query->orderBy('price', 'ASC');
                }
                if ($price_order == 2) {
                    $query->orderBy('price', 'DESC');
                }
                break;
            }
        }
        $payload = (object)['productModel' => $query];
        event('ShopBeforeGetCatProducts', $payload);
        return $payload->productModel->paginate($per_page)->toArray();
    }

    public function getComments($attributes)
    {
        return CommentLogic::instance()->getList($attributes);
    }

    
    /**
     * 更新商品销量
     * @param OrderProductsModel $order 订单商品
     * @param String $type INC:增加，Dec：减少
     */
    public function updateProductSold($orderProducts, $type = 'INC')
    {
        $method = $type == 'DEC' ? 'decrement' : 'increment';
        foreach ($orderProducts as $row) {
            $productQuery = $this->static::where('id', $row->product_id);
            $skuQuery = ProductSkuModel::where('id', $row->sku_id);
            $productQuery->$method('sold_count', $row->quantity);
            $skuQuery->$method('sold_count', $row->quantity);
        }
    }

    /**
     * 更新商品库存
     * @param OrderProductsModel array $order 订单商品
     * @param String $type INC:增加，Dec：减少
     * @param Int $scene 场景 1： 付款 2：下单
     */
    public function updateProductsStock($orderProducts, $type = 'INC')
    {   
        $method = $type == 'DEC' ? 'decrement' : 'increment';
        foreach ($orderProducts as $row) {
            ProductSkuModel::where('id', $row->sku_id)->$method('stock', $row->quantity);
        }
    }

    /**
     * 获取小程序码
     */
    public function getWxaQrcode($page, $scene, $option = [])
    {
        $dir = md5($page);
        $name = md5($scene);
        $path = PUBLIC_PATH . DS . 'storage' . DS . 'wxa' . DS . $dir . DS;
        if (file_exists($path . $name . '.png')) return "/storage/wxa/{$dir}/{$name}.png";
        $scene = get_module_group_config('shop', 'third')['scene'];
        $app = ev('WechatApp', 'MINIAPP', $scene);
        $option['page'] = $page;
        $response = $app->app_code->getUnlimit($scene, $option);
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->saveAs($path, $name . '.png');
            return "/storage/wxa/{$dir}/{$name}.png";
        }
        throw new Exception($response['errmsg']);
    }
}