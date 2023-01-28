<?php

namespace app\shop\resolvers;

use Yi\GraphQL\Exception;
use app\shop\logic\api\SkuLogic;
use app\shop\logic\api\OrderLogic;
use app\area\model\api\AddressModel;
use app\shop\model\api\OrderModel;

class Order 
{
    public function getPrice($fieldValue)
    {
        try {
            $products = OrderLogic::instance()->getSkuProducts($fieldValue->getArgs());
            $address_id = $fieldValue->getArg('address_id');
            $address = AddressModel::where('user_id', get_user()->id)->find($address_id);
            if (!$address) throw new Exception("地址不存在");
            $use_money = $fieldValue->getArg('use_money');
            $use_score = $fieldValue->getArg('use_score');
            $skuLogic = SkuLogic::instance()->useMoney(!empty($use_money))->useScore(!empty($use_score));
            $skuLogic->clacPrice($products, $address->address_id);
            return $skuLogic->toArray();
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function bySelf($fieldValue)
    {
        $query = $fieldValue->getValue();
        $query->where('user_id', get_user()->id);
        return $query;
    }

    public function byState($fieldValue)
    {
        $query = $fieldValue->getValue();
        $state = $fieldValue->getArg('state');
        switch ($state) {
            case 1: { // 待付款
                $query->where('status', OrderModel::ORDER_STATUS_NORMAL);
                break;
            }
            case 2: { // 待发货
                $query->where('status', OrderModel::ORDER_STATUS_WAIT_SHIP);
                break;
            }
            case 3: { // 待收货                
                $query->where('status', OrderModel::ORDER_STATUS_WAIT_RECEIV);
                break;
            }
            case 4: { // 待评价
                $query->where('buyer_comment', 0)->whereIn('status', [OrderModel::ORDER_STATUS_RECEIVED, OrderModel::ORDER_STATUS_DONE]);
                break;
            }
            case 5: { // 售后
                $query->where('after_sale_status', '<>', OrderModel::AFTER_SALE_NORMAL);
                break;
            }
            default: {
                break;
            }
        }
        return $query;
    }

    public function create($fieldValue)
    {
        $data = \app\pay\logic\index\OrderLogic::instance()->create($fieldValue->getArgs(), 'shop', (float)get_module_group_config('shop', 'order', 'order_life') * 60);
        return $data['out_order_sn'];
    }

    public function cancel($fieldValue)
    {
        try {
            return \app\pay\logic\index\OrderLogic::instance()->cancel(['out_order_sn' => $fieldValue->getArg('order_sn')], 'shop');
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($fieldValue)
    {
        $order_sn = $fieldValue->getArg('order_sn');
        return OrderLogic::instance()->del(['order_sn' => $order_sn]);
    }

    public function applyPostSale($fieldValue)
    {
        $param = $fieldValue->getArgs();
        return OrderLogic::instance()->applyRefund($param);
    }

    public function cancelPostSale($fieldValue)
    {
        $order_sn = $fieldValue->getArg('order_sn');
        return OrderLogic::instance()->cancelPostSale($order_sn);
    }

    public function getExpressTraces($fieldValue)
    {
        $order_sn = $fieldValue->getArg('order_sn');
        $order = OrderModel::where('order_sn', $order_sn)->first();
        if (!$order) throw new Exception("订单不存在");
        $code = $order->express_code;
        $no = $order->express_no;
        $phone = $order->contactor_phone;
        return ev('ExpressTraces', $code, $no, $phone);
    }

    public function receive($fieldValue)
    {
        $param = $fieldValue->getArgs();
        try {
            OrderLogic::instance()->receive($param);
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
        return true;
    }
}