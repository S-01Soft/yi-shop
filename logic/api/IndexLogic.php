<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use app\shop\model\api\OrderModel;
use app\shop\model\api\AreaModel;
use app\shop\model\api\PageTemplateModel;

class IndexLogic extends Logic
{
    public function getAppInfo()
    {
        
        $config = [];
        $modules = get_module_list();
        $plugins = ['shop'];
        foreach ($modules as $k => $v) {
            $deps = empty($v['dep']) ? [] : explode(',', str_replace(' ', '', $v['dep']));
            if (!empty($v['dep']) && in_array('shop', $deps) && $v['status'] == 1) $plugins[] = $k;
        }
        foreach ($plugins as $k => $v) {
            $itemconfig = get_module_full_config($v);
            foreach ($itemconfig as $name => $item) {
                if (!empty($item['frontend']) && $item['frontend']) $config[$v][$item['group_key']][$item['alias']] = $item['value']; //$config[$item['alias']] = $item['value'];
            }
        }

        $platform = request()->header('platform');
        $platform = strtoupper($platform);
        $templates = get_module_group_config('shop', 'template');

        if (!empty($templates[$platform])) {
            $page_setting = $this->getPageSetting($templates[$platform]);
        }
        
        if (empty($page_setting)) {
            $page_setting = $this->getDefaultPageSetting();
        }
        
        $config['page_setting'] = $page_setting;

        $data = [
            'plugins' => array_values($plugins),
            'config' => $config
        ];
        $payload = (object) [ 'data' => $data ];
        event('ShopAppInfo', $payload);
        return $payload->data;
    }

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