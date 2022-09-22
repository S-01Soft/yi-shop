<?php

namespace app\shop\events\user;

use app\shop\model\api\ProductModel;
use app\shop\model\api\OrderProductModel;
use app\shop\model\api\OrderModel;
use support\Db;

/**
 * 更新商品评价数量
 * ShopAfterUserComment
 */
class UpdateProductComment
{
    /**
     * @param Auth $user
     * @param OrderProductModel $orderProduct
     * @param CommentModel $comment
     */
    public function handle($payload)
    {
        $comment = $payload->comment;
        $product = ProductModel::where('id', $comment->product_id)->first();
        if (!empty($product)) {
            $product->comment_count = $product->comment_count + 1;
            $product->save();
        }
        $orderProduct = OrderProductModel::where('order_id', $comment->order_id)->where('buyer_comment', 0)->get();
        if ($orderProduct->isEmpty()) {
            $order = OrderModel::find($comment->order_id);
            $order->buyer_comment = 1;
            $order->save();
        }
    }
}
