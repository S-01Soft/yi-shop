<?php

namespace app\shop\logic\admin;

use support\exception\Exception;
use support\Db;
use app\shop\model\api\OrderModel as SOrderModel;
use app\shop\model\admin\OrderProductsModel;

class OrderLogic extends Logic
{
    protected $where_ignore = ['is_deleted'];

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\OrderModel::class;
        parent::initialize();
        $this->auth = get_admin();
    }

    public function paginateView($c)
    {
        $c->hidden = ['add_btn', 'slot_edit_btn', 'import_btn'];
    }

    protected function beforePaginate($query)
    {
        $query->with(['user', 'products']);
        if (!empty($this->where['is_deleted']) && !empty($this->where['is_deleted'][1])) $query->withTrashed();
    }
    protected function afterPaginate($result)
    {
        foreach($result as $row) {
            if($row->user) $row->user->setVisible(['id','nickname']);
        }
        return $result;
    }
    protected function beforeSelect($query)
    {
        $query->with(['user']);
    }
    protected function afterSelect($result)
    {
        foreach($result as $row) {
            if($row->user) $row->user->setVisible(['id','nickname']);
        }
        return $result;
    }
    protected function beforeAll($query)
    {
        $query->with(['user']);
    }
    protected function afterAll($result)
    {
        foreach($result as $row) {
            if($row->user) $row->user->setVisible(['id','nickname']);
        }
        return $result;
    }

    public function pay($id, $pay_money = 0)
    {
        $order = $this->static::where('id', $id)->first();
        if (empty($order)) throw new Exception("订单不存在");
        if ($order->is_pay == 1) throw new Exception("订单已支付");
        if ($order->status == -1) throw new Exception("订单已取消");
        if ($order->status != 0) throw new Exception("订单状态异常");
        \app\pay\logic\index\OrderLogic::instance()->paySuccess($order->order_sn, $pay_money, 'underline', 'underline');
        return $order;
    }

    public function ship($params)
    {
        extract($params);
        $order = $this->static::with(['express'])->find($id);
        if (empty($order)) throw new Exception("订单不存在");        
        if ($order->is_delivery == 1) throw new Exception("该订单已发货");
        if ($order->is_pay == 0) throw new Exception("该订单未支付");
        $order->is_delivery = 1;
        $order->delivery = time();
        $order->express_code = $express_code;
        $order->express_no = $express_no;
        $order->status = SOrderModel::ORDER_STATUS_WAIT_RECEIV;
        $payload = (object)[
            'order' => $order
        ];
        event('ShopOrderShip', $payload);
        $payload->order->save();
        return $order;
    }

    
    public function exchange($params)
    {
        extract($params);
        $order = $this->static::find($id);
        if (empty($order)) throw new Exception("订单不存在");        
        
        $order->after_sale_status = SOrderModel::AFTER_SALE_REFUND;
        $data = $order->after_sale_data;
        $data['express_code'] = $express_code;
        $data['express_no'] = $express_no;
        
        $payload = (object)[
            'order' => $order
        ];
        event('ShopOrderExchange', $payload);
        $payload->order->save();
        return $order;
    }

    public function refund($params)
    {
        Db::beginTransaction();
        try {
            $order = \app\shop\model\api\OrderModel::where('order_sn', $params['order_sn'])->first();
            if (empty($order)) throw new Exception("订单不存在");
            
            $order->refund_fee = $params['price'] + $order->refund_fee;
            $order->after_saler_remark = $params['remark'];
    
            \app\pay\logic\index\OrderLogic::instance()->refund($params['order_sn'], $params['price'], $params['type']);
            $payload = (object)['order'=> $order];
            event('ShopOrderRefund', $payload);
            $payload->order->save();
            if ($params['close'] == 1) {
                ev('ShopOrderCancel', $order->order_sn);
            }
            Db::commit();
        } catch(Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }

    public function reject($params)
    {
        extract($params);
        $order = $this->static::where('order_sn', $order_sn)->first();
        $order->after_sale_status = \app\shop\model\api\OrderModel::AFTER_SALE_REJECT;
        $order->after_saler_remark = $after_saler_remark;
        $payload = (object)[
            'order' => $order
        ];
        event('ShopOrderReject', $payload);
        $payload->order->save();
        return true;
    }

    public function edit_address($params)
    {
        extract($params);
        $order = $this->static::find($id);
        if (!$order) throw new Exception("订单不存在");
        $payload = (object)[            
            'newvalue' => $v,
            'admin' => $this->auth,
            'order' => $order
        ];
        switch ($k) {
            case 'address':
                $payload->oldvalue = $order->address;
                $order->address = $v;
                $order->save();
                event('ShopUpdateOrderAddress', $payload);
            break;
            case 'contactor':
                $payload->oldvalue = $order->contactor;
                $order->contactor = $v;
                $order->save();
                event('ShopUpdateOrderContactorName', $payload);
            break;
            case 'contactor_phone' :
                $payload->oldvalue = $order->contactor_phone;
                $order->contactor_phone = $v;
                $order->save();
                event('ShopUpdateOrderContactorPhone', $payload);
            break;
        }
    }

    protected function afterDelete()
    {
        $order_sn = request()->post('where')['order_sn'][1] ?? [];
        $payload = (object)[
            'order_sn' => $order_sn
        ];
        event('OrderDelete', $payload);
    }
}