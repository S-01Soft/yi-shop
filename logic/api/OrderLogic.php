<?php

namespace app\shop\logic\api;

use yi\User;
use support\exception\Exception;
use support\Db;
use app\area\model\api\AddressModel;
use app\shop\model\api\CartModel;
use app\shop\model\api\ProductModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\model\api\OrderProductModel;

class OrderLogic extends Logic
{
    
    protected function initialize()
    {
        parent::initialize();
        $this->static = \app\shop\model\api\OrderModel::class;
    }

    public function info($attributes)
    {
        extract($attributes);
        $order = $this->static::with(['products'])->where('order_sn', $order_sn)->first();
        if (empty($order)) {
            throw new Exception("没有该订单");
        }
        $order = $order->getExpress();
        return $order;
    }
    
    public function getList(array $attributes = [])
    {
        extract($attributes);
        $user = get_user();
        $model = $this->static::with(['products', 'express'])->where('user_id', $user->id)->where('is_show', 1)->orderBy('created_at', 'DESC');
        $state = empty($state) ? 0 : $state;
        switch ($state) {
            case 1: { // 待付款
                $model->where('status', $this->static::ORDER_STATUS_NORMAL);
                break;
            }
            case 2: { // 待发货
                $model->where('status', $this->static::ORDER_STATUS_WAIT_SHIP);
                break;
            }
            case 3: { // 待收货                
                $model->where('status', $this->static::ORDER_STATUS_WAIT_RECEIV);
                break;
            }
            case 4: { // 待评价
                $model->where('buyer_comment', 0)->whereIn('status', [$this->static::ORDER_STATUS_RECEIVED, $this->static::ORDER_STATUS_DONE]);
                break;
            }
            case 5: { // 售后
                $model->where('after_sale_status', '<>', $this->static::AFTER_SALE_NORMAL);
                break;
            }
            default: {
                break;
            }
        }
        return $model->paginate(10);
    }

    public function onCreate($form)
    {
        $products = $this->getSkuProducts($form);
        $form['products'] = $products;
        $form['address_id'] = $form['address_id'] ?? 0;
        return $this->add($form);
    }

    /**
     * 构造skuLogic products数据
     * 1、购物车购买 无参数
     * 2、直接购买 product => ['sku_id', 'quantity'] 
     */
    public function getSkuProducts($attributes = [])
    {
        extract($attributes);
        if (!empty($product)) {
            $products = [$product];
        } else {
            $data = CartLogic::instance()->getList(1);
            if ($data->isEmpty()) throw new Exception("购物车没有商品");
            $products = [];
            foreach ($data as $item) {
                $products[] = [
                    'sku_id' => $item->sku->id,
                    'quantity' => $item->quantity
                ];
            }
        }
        return $products;
    }

    /**
     * 创建订单
     * @param List $list
     * @param Integer $address_id
     */
    public function add($attributes)
    {
        extract($attributes);
        $user = get_user();
        if (empty($products)) {
            throw new Exception("没有商品");
        }
        $address = AddressModel::find($address_id);
        if (empty($address) || $address->user_id != $user->id) {
            throw new Exception("请选择收货地址");
        }
        Db::beginTransaction();
        $skuLogic = SkuLogic::instance()->useMoney(!empty($use_money))->useScore(!empty($use_score))->clacPrice($products, $address->address_id);
        $config = get_module_config('shop');
        $order_sn = ev('CreateOrderNo', 'S');
        try {
            $data = [
                'order_sn' => $order_sn,
                'user_id' => $user->id,
                'contactor' => $address->contactor_name,
                'contactor_phone' => $address->phone,
                'address' => $address->address . ' ' . $address->street,
                'remark' => $remark ?? '',
                'status' => 0
            ];
            $order = new $this->static($data);
            $payload = (object)[
                'order' => $order,
                'skuLogic' => $skuLogic
            ];
            try {
                event('ShopBeforeOrderCreated', $payload);
            } catch (\support\exception\Exception $e) {
                Db::rollBack();
                throw new Exception($e->getMessage());
            }
            $order->delivery_price = $skuLogic->delivery_price;
            $order->products_price = $skuLogic->products_price;
            $order->discount_price = $skuLogic->discount_price;
            $order->score_price = $skuLogic->use_score_price;
            $order->money_price = $skuLogic->use_money_price;
            $order->order_price = $skuLogic->order_price;
            $order->use_score = $skuLogic->use_score;
            $order->save();
            $order = $this->static::find($order->id);
            $product_data = [];
            foreach ($skuLogic->products as $i => $row) {
                if (!$row->product->on_sale) throw new Exception("商品【" . $row->product->title . '】已下架');
                $stock_type = empty($row->product['stock_type']) ? (int)$config['stock_type']: $row->product['stock_type'];
                $item = [
                    'order_id' => $order->id,
                    'product_id' => $row->product['id'],
                    'sku_id' => $row->id,
                    'shop_id' => 0,
                    'title' => $row->product['title'],
                    'description' => $row->product['description'],
                    'image' => implode(',', $row->product['image']),
                    'attributes' => $row->value,
                    'unit' => $row->product->unit->code,
                    'price' => $row->price,
                    'quantity' => $row->quantity,
                    'freeze_stock' => $stock_type == 2 ? $row->quantity : 0,
                    'product_price' => $row->price * $row->quantity,
                    'discount_price' => 0,
                    'stock_type' => $stock_type,
                    'auto_receive_time' => $row->product['auto_receive_time']
                ];
                $product_data[] = $item;
            }
            if (empty($product_data)) {
                Db::rollBack();
                throw new Exception("没有选择商品");
            }
            $OrderProduct = new OrderProductModel;
            $OrderProduct->insert($product_data);

            $orderProducts = OrderProductModel::with(['sku'])->where('order_id', $order->id)->get();
            $error = false;

            if ($error !== false) {
                Db::rollBack();
                throw new Exception($error);
            }
            
            CartModel::where('user_id', $user->id)->where('is_selected', 1)->delete();

            $payload = (object)[
                'order' => $order,
                'skuLogic' => $skuLogic
            ];
            event('ShopAfterOrderCreated', $payload);
            Db::commit();
            return [
                'title' => $config['__APP_NAME__'] . '-订单流水号' . $order->order_sn,
                'price' => $order->order_price,
                'out_order_sn' => $order->order_sn
            ];
        } catch (\PDOException $e) {
            Db::rollBack();
            throw new Exception($e->getMessage());
        } catch (\support\exception\Exception $e) {
            Db::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function onSuccess($unionOrder, $form)
    {
        $order = $this->static::where('order_sn', $unionOrder->out_order_sn)->first();
        if (empty($order)) return false;
        if ($this->static::WAIT_PAY !== $order->is_pay) return false;
        $order->is_pay = $this->static::PAY_SUCCESS;
        $order->pay_time = time();
        $order->payed_price = $form['payamount'];
        $order->pay_type = $form['pay_type'];
        $order->pay_method = $form['pay_method'];
        $order->status = $this->static::ORDER_STATUS_WAIT_SHIP;
        $payload = (object)['order' => $order];
        event('ShopOrderPayOk', $payload);
        $payload->order->save();
        return $payload->order;
    }

    /**
     * 获取创建订单的价格
     * @param Integer $address_id require
     * @param Integer $sku_id
     * @param Integer @quantity
     */
    public function getPrice($attributes)
    {
        extract($attributes);
        $products = $this->getSkuProducts($attributes);
        $address = AddressModel::find($address_id);
        $skuLogic = SkuLogic::instance()->useMoney(!empty($use_money))->useScore(!empty($use_score));
        $skuLogic->clacPrice($products, $address->address_id);
        return [
            'discount_price' => $skuLogic->discount_price,
            'products_price' => $skuLogic->products_price,
            'score_price' => $skuLogic->score_price,
            'money_price' => $skuLogic->money_price,
            'delivery_price' => $skuLogic->delivery_price,
            'order_price' => $skuLogic->order_price
        ];
    }
    
    /**
     * 构造立即购买的数据结构
     * @param Integer $sku_id
     * @param Integer $quantity
     */
    public function buyOneInfo($attributes)
    {
        extract($attributes);
        $user = get_user();
        $sku = ProductSkuModel::find($sku_id);
        $product = ProductModel::where('id', $sku->product_id)->first();
        $cart = new CartModel;
        $cart->sku = $sku;
        $cart->product = $product;
        // $cart->delivery = $delivery;
        $cart->quantity = intval($quantity);
        $cart->is_selected = 1;
        $list = [$cart];
        // $list = [['sku' => $sku, 'product' => $product, 'quantity' => (int)$quantity, 'is_selected' => 1]];
        
        return $list;
    }

    /**
     * 订单确认收货
     * @param Integer $order_sn 订单sn
     */
    public function receive($attributes)
    {
        extract($attributes);
        $user = get_user();
        $order = $this->static::where('order_sn', $order_sn)->where('user_id', $user->id)->first();
        if (empty($order)) {
            throw new Exception("没有该订单");
        }
        // if ($order->status != 1) {
        //     throw new Exception("订单状态异常，不能收货");
        // }
        if ($order->is_pay != 1) {
            throw new Exception("订单未付款");
        }
        if ($order->is_received != 0) {
            throw new Exception("订单已收货");
        }
        $payload = (object)[
            'user' => $user,
            'order' => $order
        ];
        event('ShopBeforeOrderReceived', $payload); // 订单收货前
        $order->is_received = 1;
        $order->received_time = time();
        $order->status = $this->static::ORDER_STATUS_RECEIVED;
        event('ShopAfterOrderReceived', $payload); // 订单收货后
        $payload->order->save();
        return true;
    }

    /**
     * 取消订单
     * @param Integer $order_sn 订单sn
     * @param UserModel $user
     */
    public function onCancel($order_sn, $param)
    {
        $order = $this->static::where('order_sn', $order_sn)->first();
        if (empty($order)) {
            throw new Exception("没有该订单");
        }
        if ($order->status == -1) {
            throw new Exception("订单已取消");
        }
        $payload = (object)[
            'order' => $order
        ];
        event('ShopBeforeCancelOrder', $payload); // 订单取消前
        $payload->order->status = -1;
        $payload->order->save();
        event('ShopAfterCancelOrder', $payload); // 订单取消后
        return true;
    }
    /**
     * 删除订单
     * @param Integer $order_sn 订单sn
     */
    public function del($attributes)
    {
        extract($attributes);
        $user = get_user();
        $order = $this->static::where('order_sn', $order_sn)->where('user_id', $user->id)->first();
        if (empty($order)) {
            throw new Exception("没有该订单");
        }
        if (in_array($order->status, [0, 1, 2]) && $order->status != -1) {
            throw new Exception("请先取消订单");
        }
        $payload = (object)[
            'user' => $user,
            'order' => $order
        ];
        event('ShopBeforeDeleteOrder', $payload); // 订单删除前
        $order->is_show = 0;
        $order->delete_time = time();
        $order->save();
        event('ShopAfterDeleteOrder', $payload); // 订单删除后
        $order_payload = (object)[
            'order_sn' => $order->order_sn
        ];
        event('OrderDelete', $order_payload);
        return true;
    }

    /** 申请售后 */
    public function applyRefund($attributes)
    {
        extract($attributes);
        $order = $this->static::where('order_sn', $order_sn)->first();
        if (empty($order)) throw new Exception("没有该订单");
        $status = intval($order->status);
        if (!in_array($status, [$this->static::ORDER_STATUS_WAIT_SHIP, $this->static::ORDER_STATUS_WAIT_RECEIV, $this->static::ORDER_STATUS_RECEIVED, $this->static::ORDER_STATUS_DONE])) {
            throw new Exception("该订单不可以退款");
        }
        $after_sale_status = intval($order->after_sale_status);
        switch ($after_sale_status) {
            case $this->static::AFTER_SALE_APPLY_REFUND : 
                throw new Exception("请等待商户审核");
                break;
            case $this->static::AFTER_SALE_REFUND :
                throw new Exception("该订单已退款");
                break;
            default :
                break;
        }

        $order->after_sale_status = $this->static::AFTER_SALE_APPLY_REFUND;
        $order->after_sale_data = json_encode(['type' => $type, 'no' => $no, 'remark' => $remark], JSON_UNESCAPED_UNICODE);
        $payload = (object)[
            'order' => $order
        ];
        event('ShopOrderApplyRefund', $payload);
        $payload->order->save();
        return true;
    }

    /**
     * 取消售后
     */
    public function cancelPostSale($order_sn)
    {
        $order = $this->static::where([['order_sn', '=', $order_sn], ['user_id', '=', get_user()->id]])->first();
        if (!$order) throw new Exception("订单不存在");
        $order->after_sale_status = 0;
        $order->after_sale_data = '{}';
        $payload = (object)[
            'order' => $order
        ];
        event('ShopOrderCancelPostsale', $payload);
        $payload->order->save();
        return true;
    }
    

}