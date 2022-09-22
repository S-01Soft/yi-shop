<?php

namespace app\shop\logic\admin;

use app\shop\model\admin\PageTemplateModel;

class PageGroupLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\PageGroupModel::class;
        parent::initialize();
    }

    public function paginateView($c)
    {
        $c->hidden = ["import_btn","export_btn"];
    }

    public function useTemplate($id)
    {
        $this->static::where('id', '<>', $id)->update(['is_default' => 0]);
        $this->static::where(['id' => $id])->update(['is_default' => 1]);
    }
}