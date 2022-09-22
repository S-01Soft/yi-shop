<?php

namespace app\shop\controller\api;

use app\shop\validate\api\CartValidate;
use support\exception\Exception;
use yi\exception\ValidateException;

class Cart extends Base 
{
    public $needLogin = ['*'];
    public $noNeedLogin = [];
    
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\CartLogic;
    }

    public function index()
    {
        try {
            $rtn = $this->logic->getList();
            return $this->success($rtn);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function add()
    {
        $param = $this->request->post();
        try {
            validate(CartValidate::class . '.add')->check($param);
            return $this->success($this->logic->add($param));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function edit()
    {
        $param = $this->request->post();
        try {
            validate(CartValidate::class . '.edit')->check($param);
            return $this->success($this->logic->edit($param));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function updateStatus()
    {
        $param = $this->request->post();
        try {
            // validate(CartValidate::class . '.update_status')->check($param);
            return $this->success($this->logic->updateStatus($param));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function delete()
    {
        $param = $this->request->post();
        try {
            validate(CartValidate::class . '.delete')->check($param);
            return $this->success($this->logic->del($param));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function clear()
    {
        try {
            return $this->success($this->logic->clear());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}