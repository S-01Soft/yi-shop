<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use yi\User;
use app\shop\model\api\ProductModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\model\api\DeliveryModel;
use app\area\model\api\AreaModel;

class SkuLogic extends Logic
{
    protected $skus = []; // ProductSku::class
    public $products = []; // <list> ProductSkuModel::class
    public $products_price = 0; // 商品总价
    public $delivery_price = 0; // 运费
    public $discount_price = 0; // 总优惠
    public $use_score = 0; // 使用积分
    public $score_price = 0; // 积分抵扣
    public $money_price = 0; // 余额抵扣
    public $order_price = 0; // 订单最终价格
    public $use_score_price = 0;
    public $use_money_price = 0;
    public $_use_money = false;
    public $_use_score = false;
    protected $address = null;

    protected function initialize()
    {
        parent::initialize();
    }

    public function product($products)
    {
        $products_temp = array_combine(array_column($products, 'sku_id'), $products);
        $this->products = ProductSkuModel::with(['product' => function($q) {
            $q->with(['unit']);
        }])->whereIn('id', array_column($products, 'sku_id'))->get();
        foreach ($this->products as &$sku) {
            $sku->quantity = $products_temp[$sku->id]['quantity'];
        }
        return $this;
    }
    
    /**
     * 计算商品实际总价格
     */
    public function clacRealProductsPrice()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->quantity * $product->price;
        }
        $this->products_price = number_format($total, 2, '.', '');
        return $this;
    }

    /**
     * 计算运费
     */
    public function clacDeliveryPrice($area_id)
    {
        $price = 0;
        foreach ($this->products as $product) {
            $price += $this->calcProductDeliveryPrice($product, $area_id);
        }
        $this->delivery_price = $price;
        $payload = (object)[
            'skuLogic' => $this
        ];
        event('ShopCalcProductsDeliveryPrice', $payload);
        return $payload->skuLogic;
    }

    /**
     * 计算单个商品运费
     */
    protected function calcProductDeliveryPrice($product, $area_id)
    {
        $price = 0;
        list($rule, $delivery) = $this->getDeliveryRule($product, $area_id);
        switch ($delivery->type) {
            case 0: { // 按重量计费
                $weigh = $product['quantity'] * $product['weight'];
                $rest_weigh = ceil($weigh - 1);
                $rest_weigh = $rest_weigh < 0 ? 0 : $rest_weigh;
                $price = $rule->first_price + $rest_weigh * $rule->rest_price;
                break;
            }
            case 1: { // 按数量计费
                $count = $product['quantity'];
                $price = $rule->first_price + ($count - 1) * $rule->rest_price;
                break;
            }
        }
        return $price;
    }

    /**
     * 获取商品运费模板、适用规则
     */
    protected function getDeliveryRule($product, $area_id)
    {
        $delivery = $this->getDeliveryTpl($product);

        if (empty($delivery)) {
            throw new Exception("商家没有设置运费规则");
        }
        $address = $this->getAddress($area_id);
        if (empty($address)) {
            throw new Exception("您的地址信息错误");
        }
        foreach ($delivery->deliveryRules as $row) { // 查找完全一致的地址Id
            if (in_array($address->id, $row->area_ids)) {
                $rule = $row;
                break;
            }
        }
        if (empty($rule)) {
            if ($address->level == 2) { // 查找上级地址
                foreach ($delivery->deliveryRules as $row) {
                    if (in_array($address->pid, $row->area_ids)) {
                        $rule = $row;
                        break;
                    }
                }
            }
        }
        if (empty($rule)) {
            foreach ($delivery->deliveryRules as $row) {
                if (in_array(0, $row->area_ids)) {
                    $rule = $row;
                    break;
                }
            }
        }
        if (empty($rule)) {
            throw new Exception("该地区暂时缺货");
        }
        return [$rule, $delivery];
    }

    /**
     * 获取运费模板
     */
    protected function getDeliveryTpl($product)
    {
        if (empty($this->deliveryList)) {
            $deliveryList = DeliveryModel::with(['deliveryRules'])->get();
            $newDeliveryList = [];
            foreach ($deliveryList as $row) {
                $newDeliveryList[$row['id']] = $row;
            }
            $this->deliveryList = $newDeliveryList;
        }
        return $this->deliveryList[$product['product']['delivery_tpl_id']] ?? $this->getDefaultDeliveryTpl();
    }

    protected function getDefaultDeliveryTpl()
    {
        $this->defaultDeliveryTpl = $this->defaultDeliveryTpl ?? DeliveryModel::with(['deliveryRules'])->orderBy("is_default", "DESC")->orderBy("id", 'ASC')->first();
        return $this->defaultDeliveryTpl;
    }

    /**
     * 获取地址
     */
    protected function getAddress($area_id)
    {
        if (empty($this->address)) {
            $this->address = AreaModel::where('id', $area_id)->first();
        }
        if ($this->address->level == 3) { // 计算运费只到市一级，区县地址重定向到市一级
            $this->address = AreaModel::find($this->address->pid);
        }
        return $this->address;

    }

    /** 计算优惠金额 */
    public function clacDiscountPrice()
    {
        $payload = (object)[
            'skuLogic' => $this
        ];
        event('ShopCalcDiscountPrice', $payload);
        return $payload->skuLogic;
    }

    /** 计算商品最后价格 */
    public function clacOrderPrice()
    {
        $order_price = number_format($this->products_price + $this->delivery_price - $this->discount_price, 2, '.', '');
        $this->order_price = $order_price > 0 ? $order_price : 0;
        return $this;
    }

    /** 计算各项价格 */
    public function clacPrice($products, $area_id)
    {
        $this->product($products)
            ->clacRealProductsPrice()   // 计算商品价格
            ->clacDeliveryPrice($area_id)       // 计算运费
            ->clacDiscountPrice()           // 计算优惠
            ->clacOrderPrice();      // 计算订单价格
        $payload = (object)[
            'skuLogic' => $this
        ];
        event('ShopAfterCalcPrice', $payload);
        return $payload->skuLogic;
    }

    public function useMoney($use_money)
    {
        $this->_use_money = $use_money;
        return $this;
    }

    public function useScore($use_score)
    {
        $this->_use_score = $use_score;
        return $this;
    }

    /**
     * 计算积分可抵扣金额
     */
    public function calcScorePayPrice()
    {
        $price = 0;
        foreach ($this->products as $item) {
            $price += $item['price'] * $item['quantity'] * ($item['product']['score_persent'] / 100);
        }
        if ($this->order_price <= $price) $price = $this->order_price;
        $price = number_format($price, 2, '.', '');
        $score_to_money = get_module_config('shop')['score_to_money'];
        $score_price = number_format(get_user()->score / $score_to_money, 2, '.', '');
        $price = min($price, $score_price);
        $this->score_price = $price;
        if ($this->_use_score) {
            $this->order_price = number_format((float)($this->order_price - $price), 2, '.', '');
            $this->use_score = $this->score_price * $score_to_money;
            $this->use_score_price = $price;
        }
        return $this;
    }

    public function calcMoneyPayPrice()
    {
        $this->money_price = min(get_user()->money, $this->order_price);
        if ($this->_use_money) {
            $this->use_money_price = $this->money_price;
            $this->order_price = number_format((float)($this->order_price - $this->money_price), 2, '.', '');
        }
        return $this;
    }

    public function toArray()
    {
        return [
            'money_price' => $this->money_price,
            'use_money_price' => $this->use_money_price,
            'score_price' => $this->score_price,
            'use_score_price' => $this->use_score_price,
            'delivery_price' => $this->delivery_price,
            'discount_price' => $this->discount_price,
            'order_price' => $this->order_price,
            'products_price' => $this->products_price
        ];
    }
}
