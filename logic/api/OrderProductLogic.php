<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use yi\User;
use app\shop\model\api\CommentModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\model\api\OrderModel;

class OrderProductLogic extends Logic
{
    
    protected function initialize()
    {
        $this->static = \app\shop\model\api\OrderProductModel::class;
        parent::initialize();
        $this->auth = get_user();
    }

    /**
     * 评价订单商品
     */
    public function comment($attributes)
    {
        extract($attributes);
        $content = empty($content) ? '' : $content;
        $orderProduct = $this->static::with(['order', 'sku', 'orderProductComments' => function ($q) {
            $q->where('user_id', $this->auth->id);
        }])->where('id', $id)->first();
        if (empty($orderProduct)) {
            throw new Exception("商品不存在");
        }
        if (empty($orderProduct->order)) {
            throw new Exception("订单不存在");
        }
        if ($orderProduct->order['user_id'] != $this->auth->id) {
            throw new Exception("您不能这么做");
        }
        if ($orderProduct->order['status'] != OrderModel::ORDER_STATUS_RECEIVED) {
            throw new Exception("您不能评价该商品");
        }
        if (!$orderProduct->orderProductComments->isEmpty()) {
            throw new Exception("商品已评价");
        }

        $comment = new CommentModel;
        $comment->user_id = $this->auth->id;
        $comment->order_product_id = $orderProduct->id;
        $comment->order_id = $orderProduct->order_id;
        $comment->product_id = $orderProduct->product_id;
        $comment->sku_id = $orderProduct->sku->id;
        $comment->images = is_array($images) ? implode(',', $images) : $images;
        $comment->content = $content;
        $comment->star = $star;
        $payload = (object)[
            'user' => $this->auth->getUser(),
            'orderProduct' => $orderProduct,
            'comment' => $comment
        ];
        event('ShopBeforeUserComment', $payload); // 用户评价商品前

        $result = ev('TextCheck', $content);
        if ($result === false) throw new Exception("内容不合法");
        if ($result === true) $comment->status = 1;
        else $comment->status = 0;
        $comment->save();
        $orderProduct->buyer_comment = 1;
        $orderProduct->save();
        event('ShopAfterUserComment', $payload); // 用户评价商品后
        return $comment;
    }

}