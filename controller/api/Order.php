<?php

namespace app\shop\controller\api;

use app\shop\validate\api\OrderValidate;
use support\exception\Exception;
use yi\exception\ValidateException;
use app\shop\validate\api\OrderProductValidate;
use app\shop\logic\api\OrderProductLogic;

class Order extends Base 
{    
    public $needLogin = ['*'];
    public $noNeedLogin = [];

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\OrderLogic;
    }

    public function index()
    {
        $params = $this->request->get();
        try {
            validate(OrderValidate::class . '.state')->check($params);
            
            return $this->success($this->logic->getList($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function info() 
    {
        $params = $this->request->get();
        try {
            validate(OrderValidate::class . '.info')->check($params);
            $order = $this->logic->info($params);
            $payload = (object)[
                'order' => $order
            ];
            event('ShopBeforeOrderResponse', $payload);
            return $this->success($payload->order);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function add() 
    {
        $form = $this->request->post();
        try {
            $data = \app\pay\logic\index\OrderLogic::instance()->create($form, 'shop', (float)get_module_group_config('shop', 'order', 'order_life') * 60);
            return $this->success($data['out_order_sn']);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function receive() 
    {
        $params = $this->request->post();
        try {
            validate(OrderValidate::class . '.sn')->check($params);
            return $this->success($this->logic->receive($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function cancel() 
    {
        $params = $this->request->post();
        $params['user'] = get_user()->getUser();
        try {
            validate(OrderValidate::class . '.sn')->check($params);
            $data = \app\pay\logic\index\OrderLogic::instance()->cancel(['out_order_sn' => $params['order_sn']], 'shop');
            return $this->success($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function del() 
    {
        $params = $this->request->post();
        
        try {
            validate(OrderValidate::class . '.sn')->check($params);
            return $this->success($this->logic->del($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function comment() 
    {
        $params = $this->request->post();
        try {
            validate(OrderProductValidate::class . '.comment')->check($params);
            return $this->success(OrderProductLogic::instance()->comment($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function getPrice() 
    {
        $params = $this->request->post();
        $rules = [
            'address_id' => 'require'
        ];
        $msg = [
            'address_id' => '请选择收货地址'
        ];
        try {
            validate($rules, $msg)->check($params);
            return $this->success($this->logic->getPrice($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function applyRefund()
    {
        $params = $this->request->post();
        $rules = [
            'order_sn' => 'require',
            'type' => 'in:1,2,3',
            'remark|备注' => 'max:200'
        ];
        try {
            validate($rules)->check($params);
            return $this->success($this->logic->applyRefund($params));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 取消售后
     */
    public function cancelPostSale()
    {
        $params = $this->request->post();
        $rules = [
            'order_sn' => 'require'
        ];
        try {
            validate($rules)->check($params);
            return $this->success($this->logic->cancelPostSale($params['order_sn']));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function getExpressInfo()
    {
        $params = $this->request->post();
        $rules = [
            'order_sn' => 'require',
        ];
        try {
            validate($rules)->check($params);
            $order = $this->logic->info($params);
            $code = $order->express_code;
            $no = $order->express_no;
            $phone = $order->contactor_phone;
            $result = ev('ExpressTraces', $code, $no, $phone);
            return $this->success($result);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 提交支付
     */
    public function pay()
    {
        try {
            $form = $this->request->post();
            $data = \app\pay\logic\index\OrderLogic::instance()->pay($form);
            return $this->success($data);
        } catch (\Yansongda\Pay\Exceptions\InvalidGatewayException $e) {
            return $this->error($e->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidSignException $e) {
            return $this->error("验签失败：" . $e->getMessage());
        } catch (\Yansongda\Pay\Exceptions\InvalidConfigException $e) {
            return $this->error($e->getMessage());
        } catch (\Yansongda\Pay\Exceptions\GatewayException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}