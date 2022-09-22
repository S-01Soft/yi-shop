<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use app\shop\model\api\OrderModel;
use app\shop\model\api\AreaModel;
use app\shop\model\api\PageTemplateModel;

class IndexLogic extends Logic
{
    public function getArea()
    {
        return AreaModel::get();
    }

    public function getPage($id)
    {
        $page = PageTemplateModel::find($id);
        if (!$page) throw new Exception("页面不存在");
        $platform = request()->header('platform');
        $platform = strtoupper($platform);
        $data = [];
        $content = $page->content;
        foreach ($content['list'] as &$v) {
            unset($v['title']);
            if (empty($v['option']['platform']) || !in_array($platform, $v['option']['platform'])) {
                unset($v['option']['platform']);
                $data[] = $v;
            }
        }
        $content['list'] = $data;
        return $content;
    }

    public function getPageSetting($id)
    {
        $model = \app\shop\model\admin\PageGroupModel::where('id', $id)->first();
        if ($model) return $model->content;
        return null;
    }

    public function getDefaultPageSetting()
    {
        $model = \app\shop\model\admin\PageGroupModel::where('is_default', 1)->first();
        return $model->content ?? null;
    }

}