<?php

namespace app\shop\subscribe;

use support\Str;
use support\Db;
use yi\Widget;
use app\shop\model\api\OrderModel;
use app\shop\logic\api\ProductLogic;
use app\shop\logic\api\CategoryLogic;
use app\shop\model\api\ProductModel;
use app\shop\model\admin\SearchModel;

class Event 
{
    protected $name = 'shop';

    protected $orderTypes = [];

    public function onHttpRun()
    {
        ev('ShopAddOrderType', [ 'type' => 0, 'title' => '普通订单' ]);
        $widgets = [
            [ 'before_form_item', function($item, $type) {
                switch ($type) {
                    case 'local':
                        echo get_view()::fetch(get_template($this->name, 'admin/public/message/setting-form-item'));
                        break;
                }
            }, [], 10000, 'message', 'admin.setting' ],
            [ 'after_content', get_template($this->name, 'admin/public/message/setting-content'), [], 10000, 'message', 'admin/setting' ],
            [ 'scripts', get_template($this->name, 'admin/public/message/setting-scripts'), [], 10000, 'message', 'admin/setting'],
            [ 'style', get_template($this->name, 'admin/public/styles'), [], 10000, 'shop' ],
        ];
        Widget::group('admin')->add($widgets);
        $widgets = [
            [ 'shop_product_item', function($data, $params) {
                echo config('view.handler')::fetch(get_template('shop/widgets/product-item'), ['params' => $params]);
            }],
            [ 'cms_after_user_menu', get_template('shop/user/menu')],
            [ 'cms_user_nav_menu_after', get_template('shop/widgets/top-menu') ],
        ];
        Widget::group('index')->add($widgets);
    }
    
    public function onBeforeAdminController()
    {
        $module = request()->getModule();
        if (in_array($module, ['shop', 'message'])) {
            load_script('admin', '../modules/shop/js/component');
        }
    }

    public function onShopReplaceParams($payload)
    {
        $scene = get_module_group_config('shop', 'third')['scene'];

        $result = ev('GetThirdConfig', $scene, 'wechat');
        if (!$result) return;
        $payload->params['__MP_WECHAT_APPID__'] = $result['mini_appid'];
        $payload->params['__WECHAT_OPEN_APPID__'] = $result['open_appid'];
    }

    public function onShopHotWords($payload)
    {
        $params = $payload->params;
        $payload->result = $this->getHotWords(...$params);
    }

    public function getHotWords($count = 5)
    {
        return SearchModel::where('status', 1)->orderBy('hits', 'desc')->orderBy('weigh', 'desc')->limit($count)->pluck('keyword');
    }

    public function onShopOrderTimeOut($payload)
    {        
        $config  = get_module_group_config('shop', 'order');
        $payload->result = (float)$config['order_life'] * 60;
    }

    public function onShopGetQueueName($payload)
    {
        $config = get_module_group_config($this->name, 'queue');
        $payload->result = $config['name'];
    }

    public function onShopSearch($payload)
    {
        list($params) = $payload->params;
        $config = get_module_group_config($this->name, 'search');
        if (!empty($params['kw'])) {
            ev('QueueSend', 'ShopSaveSearchKeyword', $params);
        }
        $payload->result = ev(Str::studly('shop_search_' . $config['engine']), $params);
    }

    public function onShopCount($payload)
    {
        list($table, $groupField, $timeField, $start, $end, $where) = $payload->params;
        $query = Db::table('shop_' . $table)->whereBetween($timeField, [$start, $end]);
        if (!empty($where)) $query->where($where);
        if (!empty($groupField)) $query->groupBy($groupField);
        $payload->result = $query->count();
    }

    public function onShopSum($payload)
    {
        list($table, $sumField, $timeField, $start, $end, $where) = $payload->params;
        $query = Db::table('shop_' . $table)->whereBetween($timeField, [$start, $end]);
        if (!empty($where)) $query->where($where);
        $query->selectRaw("sum(`$sumField`) as total");
        $data = $query->get()->map(function ($row) {
            return collect($row)->toArray();
        });
        $payload->result = $data[0]['total'] ?: 0;
    }

    public function onShopOrderTypes($payload)
    {
        $payload->result = array_values($this->orderTypes);
    }

    public function onShopAddOrderType($payload)
    {
        list($item) = $payload->params;
        $this->orderTypes[$item['type']] = $item;
    }

    public function onShopStockType($payload)
    {
        list($product) = $payload->params;
        $config = get_module_group_config('shop', 'order');
        $payload->result = $product->stock_type ?: $config['stock_type'];
    }

    public function onShopH5BaseUrl($payload)
    {
        $config = get_module_group_config('shop', 'base');
        $url = $config['domain'] . $config['h5_base_path'];
        if ($config['h5_route_mode'] == 'hash') $url .= '#/';
        $payload->result = $url;
    }

    public function onShopOrderCancel($payload)
    {
        list($order_sn) = $payload->params;
        \app\pay\logic\index\OrderLogic::instance()->cancel(['out_order_sn' => $order_sn], 'shop');
    }
}