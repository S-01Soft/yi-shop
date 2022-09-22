<?php

namespace app\shop\controller\index;

use app\shop\validate\api\OrderValidate;
use support\exception\Exception;
use yi\exception\ValidateException;
use app\shop\validate\api\OrderProductValidate;
use app\shop\logic\api\OrderProductLogic;

class Order extends Base 
{

    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\api\OrderLogic::instance();
    }

    public function index()
    {
        $params = $this->request->get();
        try {
            validate(OrderValidate::class . '.state')->check($params);
            $order_list = $this->logic->getList($params);
            $this->assign('paginator', paginator($order_list));
            $this->assign('order_list', $order_list);
            return $this->fetch('shop/user/order');
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function submit()
    {
        $order_sn = request()->get('order_sn');
        return redirect('/pay/submit.html?out_order_sn=' . $order_sn);
    }

    public function comment()
    {
        try {
            $id = $this->request->get('id');
            if ($this->request->isAjax()) {
                $params = $this->request->post('form');
                $params['id'] = $id;
                validate(OrderProductValidate::class . '.comment')->check($params);
                return $this->success(OrderProductLogic::instance()->comment($params));
            }
            return $this->fetch('shop/order/comment');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}