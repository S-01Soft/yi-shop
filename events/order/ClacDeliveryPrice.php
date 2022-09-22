<?php

namespace app\shop\events\order;

use app\shop\logic\api\SkuLogic;

/**
 * 计算运费
 */
class ClacDeliveryPrice
{
    /**
     * ShopBeforeOrderCreateResponse
     * @param Auth $user
     * @param Array $productData (quantity, ProductModel $product, ProductSkuModel $sku)
     */
    public function handle($payload)
    {
        $products = $payload->productData;
        $address_id = $payload->address_id;
        SkuLogic::instance()->product($products)->clacDeliveryPrice($address_id);
    }
}
