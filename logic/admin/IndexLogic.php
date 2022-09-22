<?php

namespace app\shop\logic\admin;

use app\shop\model\admin\OrderModel;
use app\shop\model\admin\OrderProductsModel;

class IndexLogic extends Logic
{
    public function getTotalData()
    {
        $day = strtotime(date('Y-m-d',time()));
        $dayEnd = $day + 24 * 60 * 60;
        $preDay = $day - 24 * 60 * 60;
        $week = strtotime(date('Y-m-d', strtotime('this week')));
        $preWeek = $week - 7 * 24 * 60 * 60;
        $month = strtotime(date('Y-m-01 0:0:0', strtotime(date('Y-m-d'))));
        $preMonth = strtotime(date("Y-m-d H:i:s", mktime(0, 0 , 0,date("m")-1,1,date("Y"))));
        
        $list =  [
            [ 
                'title' => '今日', 
                'start' => date('Y-m-d H:i:s', $day),
                'end' => date('Y-m-d H:i:s', $dayEnd - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $day, $dayEnd - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $day, $dayEnd - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $day, $dayEnd - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $day, $dayEnd - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $day, $dayEnd - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $day, $dayEnd - 1, ['is_pay' => 0])
                ]
            ],
            [ 
                'title' => '昨日', 
                'start' => date('Y-m-d H:i:s', $preDay),
                'end' => date('Y-m-d H:i:s', $day - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preDay, $day - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preDay, $day - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preDay, $day - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preDay, $day - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preDay, $day - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preDay, $day - 1, ['is_pay' => 0])
                ]
            ],
            [ 
                'title' => '本周', 
                'start' => date('Y-m-d H:i:s', $week),
                'end' => date('Y-m-d H:i:s', $dayEnd - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $week, $dayEnd - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $week, $dayEnd - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $week, $dayEnd - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $week, $dayEnd - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $week, $dayEnd - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $week, $dayEnd - 1, ['is_pay' => 0])
                ]
            ],
            [ 
                'title' => '上周', 
                'start' => date('Y-m-d H:i:s', $preWeek),
                'end' => date('Y-m-d H:i:s', $week - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preWeek, $week - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preWeek, $week - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preWeek, $week - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preWeek, $week - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preWeek, $week - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preWeek, $week - 1, ['is_pay' => 0])
                ]
            ],
            [ 
                'title' => '本月', 
                'start' => date('Y-m-d H:i:s', $month),
                'end' => date('Y-m-d H:i:s', $dayEnd - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $month, $dayEnd - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $month, $dayEnd - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $month, $dayEnd - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $month, $dayEnd - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $month, $dayEnd - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $month, $dayEnd - 1, ['is_pay' => 0])
                ]
            ],
            [ 
                'title' => '上月', 
                'start' => date('Y-m-d H:i:s', $preMonth),
                'end' => date('Y-m-d H:i:s', $month - 1),
                'total' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preMonth, $month - 1, null),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preMonth, $month - 1, null)
                ],
                'is_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preMonth, $month - 1, ['is_pay' => 1]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preMonth, $month - 1, ['is_pay' => 1])
                ],
                'no_pay' => [
                    'count' => ev('ShopCount', 'order', null, 'created_at', $preMonth, $month - 1, ['is_pay' => 0]),
                    'money' => ev('ShopSum', 'order', 'order_price', 'created_at', $preMonth, $month - 1, ['is_pay' => 0])
                ]
            ]
        ];

        return [
            'list' => $list
        ];
    }

    public function getEchartData($form)
    {
        $data = [];
        $query = OrderModel::where('created_at', '>=', $form['start'])->where('created_at', '<=', $form['end']);
        $countQuery = clone $query;
        $countQuery->selectRaw('count(*) as total,from_unixtime(created_at, "%Y-%m-%d") as day')->groupBy('day');   
        $list = $countQuery->get()->toArray();
        $countList = array_combine(array_column($list, 'day'), array_column($list, 'total'));

        $moneyQuery = clone $query;
        $moneyQuery->selectRaw('sum(order_price) as total,from_unixtime(created_at, "%Y-%m-%d") as day')->groupBy('day');
        $list = $moneyQuery->get()->toArray();
        $moneyList = array_combine(array_column($list, 'day'), array_column($list, 'total'));
        
        $dayCount = ceil(($form['end'] - $form['start']) / 24 / 60 / 60);
        for ($i = 0; $i < $dayCount; $i ++) {
            $title = date('Y-m-d', $form['start'] + $i * 24 * 60 * 60);
            $data['count']['title'][] = $title;
            $data['count']['value'][] = $countList[$title] ?? 0;
            $data['money']['title'][] = $title;
            $data['money']['value'][] = $moneyList[$title] ?? 0;
        }

        return $data;
    }

    public function getPages()
    {
        $list = get_module_list();
        $result = [];
        foreach ($list as $v) {
            if ((isset($v['dep']) && $v['dep'] == 'shop') || $v['name'] == 'shop')
                $result = array_merge($result, $this->getModulePages($v['name']));
        }
        $tabBars = $this->getTabs();
        $res = [];
        foreach ($result as $v) {
            if (find_rows($tabBars, ['path' => $v['path']]) == -1) {
                $res[] = $v;
            }
        }
        return $res;
    }

    public function getExpandPages()
    {
        $payload = (object)[
            'pages' => []
        ];
        event('ShopGetExpandPages', $payload);
        return $payload->pages;
    }
    
    public function getTabs()
    {
        $list = get_module_list();
        $result = [];
        foreach ($list as $v) {
            if ((isset($v['dep']) && $v['dep'] == 'shop') || $v['name'] == 'shop')
                $result = array_merge($result, $this->getModuleTabs($v['name']));
        }
        return $result;
    }
    
    public function getModuleTabs($module_name)
    {
        $content = $this->parsePagesJson($module_name);
        if (empty($content['tabBar']['list'])) return [];
        foreach($content['tabBar']['list'] as $v) {
            $result[] = [
                'path' => $v['pagePath'],
                'title' => $v['text']
            ];
        }
        return $result;
    }

    public function getModulePages($module_name)
    {
        $content = $this->parsePagesJson($module_name);
        if (empty($content)) return [];
        foreach ($content['pages'] as $v) {
            $title = $v['style']['navigationBarTitleText'] ?? null;
            if ($title) {
                $result[] = [
                    'path' => $v['path'],
                    'title' => $title
                ];
            }
        }
        return $result;
    }

    public function parsePagesJson($module_name)
    {
        $file = app_path() . DS . $module_name . DS . 'frontend' . DS . 'pages.json';
        if (!is_file($file))  return [];
        $fullconfig = get_module_full_config('shop');
        $content = file_get_contents($file);
        foreach ($fullconfig as $v) {
            if (!empty($v['is_replace'])) {
                $content = str_replace($v['name'], $v['value'], $content);
            }
        }
        $content = preg_replace("/\/\/.+/", '', $content);
        $content = preg_replace("/\/\*[\s\S]+?\*\//", '', $content);
        $content = json_decode($content, true);
        return $content;
    }
}