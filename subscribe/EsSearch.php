<?php

namespace app\shop\subscribe;

use support\Str;
use yi\Widget;
use app\shop\logic\api\ProductLogic;
use app\shop\logic\api\CategoryLogic;
use app\shop\model\api\ProductModel;
use app\shop\model\admin\SearchModel;

class EsSearch 
{
    protected $name = 'shop';

    public function onShopSearchMysql($payload)
    {
        list($params) = $payload->params;
        $payload->result = ProductLogic::instance()->getList($params);
    }

    public function onShopSearchEs($payload)
    {
        list($params) = $payload->params;
        $config = get_module_group_config($this->name, 'search');
        $p = (object)[
            'index' => $config['index'],
            'paginate' => true,
            'params' => $params,
            'data' => [
                'total' => 0,
                'current_page' => $params['page'] ?? 1,
                'last_page' => 0,
                'per_page' => $params['per_page'] ?? 10,
                'data' => []
            ]
        ];
        event('EsSearch', $p);
        $result = $p->data;
        foreach ($result['data'] as &$item) {
            unset($item['parent_category_ids']);
            unset($item['tag_ids']);
        }
        $payload->result = $result;
    }

    public function onEsInitData($payload)
    {
        list($index, $es) = $payload->params;
        $config = get_module_group_config($this->name, 'search');
        if ($config['engine'] != 'es' || $config['index'] != $index) return;
        ProductModel::with(['skus', 'tags', 'unit'])->whereRaw("1=1")->chunkById(100, function($rows) use ($index, $es) {
            $result = [];
            foreach ($rows as $row) {
                $result[] = ['index'=> ['_index' => $index, '_id' => $row['id'], '_type' => '_doc']];
                $result[] = ev('ShopParseProductEsData', $row);
            }
            $es->putDocs($index, $result);
        });
    }

    public function onShopProductAfterInsert($payload)
    {
        list($row) = $payload->params;
        $params = ['id' => $row->id];
        ev('QueueSend', 'ShopPutProductEs', $params);
    }

    public function onShopProductAfterUpdate($payload)
    {
        list($row) = $payload->params;
        $params = ['id' => $row->id];
        ev('QueueSend', 'ShopPutProductEs', $params);
    }

    public function onShopProductAfterDelete($payload)
    {
        list($row) = $payload->params;
        $params = ['id' => $row->id];
        ev('QueueSend', 'ShopDeleteProductEs', $params);
    }

    public function onShopTagProductAfterInsert($payload)
    {
        list($row) = $payload->params;
        $params = ['id' => $row->id];
        ev('QueueSend', 'ShopPutProductEs', $params);
    }

    public function onShopTagProductAfterDelete($payload)
    {
        list($row) = $payload->params;
        $params = ['id' => $row->id];
        ev('QueueSend', 'ShopPutProductEs', $params);
    }

    public function onShopParseProductEsData($payload)
    {
        list($row) = $payload->params;
        $payload->result = [
            'id' => $row->id,
            'category_id' => $row->category_id,
            'parent_category_ids' => array_column(CategoryLogic::instance()->getChildren($row->category_id, true), 'id'),
            'tag_ids' => array_column($row->tags->toArray(), 'tag_id'),
            'title' => $row->title,
            'description' => $row->description,
            "attr_group" => $row->attr_group,
            'attr_items' => $row->attr_items,
            'image' => $row->image,
            'category_recommend' => $row->category_recommend,
            'on_sale' => $row->on_sale,
            'sold_count' => $row->sold_count,
            'price' => $row->price,
            'unit' => $row->unit,
            'created_at' => $row->created_at->timestamp,
            'updated_at' => $row->updated_at->timestamp,
            'skus' => $row->skus->toArray()
        ];
    }
}