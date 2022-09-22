<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use yi\User;
use support\Db;
use app\shop\model\api\ProductSkuModel;

class CartLogic extends Logic
{
    
    protected function initialize()
    {
        $this->static = \app\shop\model\api\CartModel::class;
        parent::initialize();
        $this->user = get_user()->getUser();
    }

    public function getList($type = 0)
    {
        $user = $this->user;
        $model = $this->static::with(['product', 'sku', 'deliveryTpl'])->where('user_id', $user->id);
        switch ($type) {
            case 1: {
                $model->where('is_selected', 1);
            }
            default: {
                break;
            }
        }
        $list = $model->get();
        foreach ($list as $row) {
            if (!$row->product->on_sale) $row->is_selected = 0;
        }
        return $list;
    }

    
    /**
     * 添加到购物车
     */
    public function add($attributes, $forceAppend = false)
    {
        extract($attributes);
        $sku = ProductSkuModel::with(['product'])->find($sku_id);
        if (empty($sku) || empty($sku['product'])) {
            throw new Exception("商品不存在");
        }
        $user = $this->user;
        $cart = $this->static::where("user_id", $user->id)->get();
        if ($forceAppend === false && $quantity > $sku->stock) throw new Exception("商品库存不足");
        foreach ($cart as $i => $row) {
            if ($row->sku_id == $sku_id && $forceAppend === false) {
                if ($row->quantity +$quantity > $sku->stock) throw new Exception("商品库存不足");
                $row->quantity += $quantity;
                return $row->save();
            }
        }
        $data = [
            'product_id' => $sku->product_id,
            'sku_id' => $sku_id,
            'price' => $sku->price,
            'quantity' => $quantity,
            'user_id' => $user->id
        ];
        $cart = new $this->static($data);
        $payload = (object)['cart' => $cart];
        event('ShopCartAdd', $payload);
        $payload->cart->save();
        return $payload->cart;
    }

    
    public function edit($attributes)
    {
        extract($attributes);
        $sku = ProductSkuModel::with(['product'])->find($sku_id);
        if (empty($sku) || empty($sku['product'])) {
            throw new Exception("商品不存在");
        }
        $user = $this->user;
        $cart = $this->static::where("user_id", $user->id)->where('sku_id', $sku_id)->first();
        if (empty($cart)) {
            return $this->static::add($attributes);
        }
        if ($quantity <= 0) {
            $cart->delete();
            return null;
        }
        if ($quantity > $sku->stock) {
            throw new Exception("商品库存不足");
        }
        $cart->quantity = $quantity;
        $payload = (object)['cart' => $cart];
        event('ShopCartUpdate', $payload);
        $payload->cart->save();
        return $cart;
    }
    
    /**
     * 删除
     */
    public function del($attributes)
    {
        extract($attributes);
        return $this->static::where('user_id', $this->user->id)->whereIn('id', $ids)->delete();
    }
    
    
    /**
     * 清空
     */
    public function clear()
    {
        return $this->static::where('user_id', $this->user->id)->delete();
    }

    
    /**
     * 更新购物车选中状态
     * @param Array $ids 购物车Id数组
     */
    public function updateStatus($param)
    {
        $ids = $param['ids'] ?? [];
        $user = $this->user;
        $ids = array_integer($ids);
        $products = $this->static::with(['product', 'sku'])->where('user_id', $user->id)->whereIn('id', $ids)->get();
        $this->static::where('user_id', $user->id)->update(['is_selected' => 0]);
        $this->static::where('user_id', $user->id)->whereIn('id', $ids)->update(['is_selected' => 1]);
        return $products;
    }

    
    public function checkStock($products)
    {
        foreach ($products as $i => $product) {
            if ($product['quantity'] > $product['sku']['stock']) {
                return $product;
            }
        }
        return true;
    }

}