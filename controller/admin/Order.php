<?php

namespace app\shop\controller\admin;

use support\Db;
use support\exception\Exception;
use \PDOException;
use app\shop\model\admin\OrderModel;
use app\shop\model\admin\ExpressModel;
use app\shop\model\api\OrderLogModel;

/**
 * @Menu(title=订单管理,weigh=95200,ignore=tree|tree_list,ismenu=1)
 */
class Order extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\OrderValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\OrderLogic::instance();
    }

    /**
     * @Menu(title=支付订单)
     */
    public function pay()
    {        
        $id = $this->request->post('id');
        $pay_money = $this->request->post('pay_money');
        Db::beginTransaction();
        try {
            $this->logic->pay($id, $pay_money);
            Db::commit();
            return $this->success();
        } catch (Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (PDOException $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * @Menu(title=订单发货)
     */
    public function ship()
    {        
        $form = $this->request->post('form');
        Db::beginTransaction();
        try {
            $this->logic->ship($form);
            Db::commit();
            return $this->success();
        } catch (Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (PDOException $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @Menu(title=换货)
     */
    public function exchange()
    {
        
        $form = $this->request->post('form');
        Db::beginTransaction();
        try {
            $this->logic->exchange($form);
            Db::commit();
            return $this->success();
        } catch (Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (PDOException $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * @Menu(title=订单退款)
     */
    public function refund()
    {        
        $form = $this->request->post('form');
        Db::beginTransaction();
        try {
            $this->logic->refund($form);
            Db::commit();
            return $this->success();
        } catch (Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (PDOException $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * @Menu(title=拒绝退款)
     */
    public function reject()
    {        
        $form = $this->request->post('form');
        Db::beginTransaction();
        try {
            $this->logic->reject($form);
            Db::commit();
            return $this->success();
        } catch (Exception $e) {
            Db::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (PDOException $e) {
            Db::rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @Menu(title=查看订单)
     */
    public function show()
    {
        $order_id = $this->request->get('id');
        $order = OrderModel::with(['user', 'products'])->where('id', $order_id)->first();
        $express = ExpressModel::where('code', $order['express_code'])->first();
        $logs = OrderLogModel::where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        $this->assign('order', $order);
        $this->assign('express', $express);
        $this->assign('logs', $logs);
        return $this->fetch();
    }

    /**
     * @Menu(title=修改订单地址)
     */
    public function edit_address()
    {
        try {
            $form = $this->request->post('form');
            $this->logic->edit_address($form);
            return $this->success();
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}