<?php

namespace app\shop\controller\api;

use support\exception\Exception;
use yi\exception\ValidateException;

class Invoice extends Base 
{    
    public $needLogin = ['*'];
    public $noNeedLogin = [];

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\InvoiceLogic;
    }

    public function index()
    {
        return $this->success('Hi');
    }

    public function info()
    {
        try {
            return $this->success($this->logic->getInfoList(get_user()->id));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), $e->getCode());

        }
    }

    public function addInfo()
    {
        try {
            $rules = [];
            $param = request()->post();
            validate($param)->check($rules);
            return $this->success($this->logic->addInfo($param));
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}