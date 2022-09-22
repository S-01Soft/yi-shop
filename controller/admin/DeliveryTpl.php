<?php

namespace app\shop\controller\admin;

use app\system\model\admin\AreaModel;

/**
 * @Menu(title=运费模板管理,weigh=89000)
 */
class DeliveryTpl extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\DeliveryTplValidate::class;

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\DeliveryTplLogic;
    }

    /**
     * @Menu(title=选择城市)
     */
    public function city_selector()
    {
        if ($this->request->isAjax()) {
            $list = $this->logic->getCityArray();
            return $this->success($list);
        }
        return $this->fetch();
    }
}