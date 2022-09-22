<?php

namespace app\shop\logic\admin;

use yi\Validate;
use app\shop\model\admin\DeliveryRuleModel;
use app\shop\model\api\AreaModel;

class DeliveryTplLogic extends Logic
{

    protected $toggleFields = ['is_default'];

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\DeliveryTplModel::class;
        parent::initialize();
    }

    public function paginateView($c)
    {
        $c->hidden = ['import_btn'];
    }

    protected function afterGetEdit($result)
    {
        $rules = DeliveryRuleModel::where('tpl_id', $result['id'])->get();
        $data = [];
        foreach ($rules as $rule) {
            $data[] = [
                'area_ids' => explode(',', $rule['area_ids']),
                'area_names' => explode(',', $rule['area_names']),
                'first_price' => $rule['first_price'],
                'rest_price' => $rule['rest_price']
            ];
        }
        $result->rules = $data;
        return $result;
    }

    protected function beforePostAdd($form)
    {
        unset($form['rules']);
        return $form;
    }
    protected function afterPostAdd($model, $form = []) 
    {
        $form = request()->post('form');
        return $this->editRules($form['rules'], $model->id);
    }

    protected function beforePostEdit($form, $query)
    {
        unset($form['rules']);
        return $form;
    }

    protected function afterPostEdit($model, $form = [])
    {
        $form = request()->post('form');
        DeliveryRuleModel::where('tpl_id', $model->id)->delete();
        return $this->editRules($form['rules'], $model->id);
    }

    protected function editRules($rules, $id)
    {
        $delivery_rule_data = [];
        foreach ($rules as $rule) {
            $data = [
                'tpl_id' => $id,
                'area_ids' => implode(',', $rule['area_ids']),
                'area_names' => implode(',', $rule['area_names']),
                'first_price' => $rule['first_price'],
                'rest_price' => $rule['rest_price']
            ];
            $rules = [
                'tpl_id' => 'require|number',
                'area_ids' => 'require',
                'area_names' => 'require',
                'first_price' => 'require|number|min:1',
                'rest_price' => 'require|number|min:1'
            ];
            $msg = [
                'first_price.require' => '价格不能为空',
                'first_price.number' => '价格必须是数字',
                'first_price.min' => '价格不能为负数',
                'rest_price.require' => '价格不能为空',
                'rest_price.number' => '价格必须是数字',
                'rest_price.min' => '价格不能为负数',
            ];
            $validate = new Validate($rules, $msg);
            if (!$validate->check($data)) {
                throw new \support\exception\Exception($validate->getError());
            }
            $delivery_rule_data[] = $data;
        }
        if (empty($delivery_rule_data)) throw new \support\exception\Exception("请添加运费规则");
        $deliveryRule = new DeliveryRuleModel;
        $deliveryRule->insert($delivery_rule_data);
        return $deliveryRule;
    }

    public function getCityArray()
    {
        $list = AreaModel::whereIn('level', [0, 1, 2])->get()->toArray();
        $tree = \yi\Tree::instance();
        $tree->init($list, 'id', 'pid');
        return $tree->getTreeArray(0);
    }
    
    protected function beforeAll($query)
    {
        $query->orderByRaw('is_default DESC');
    }
}