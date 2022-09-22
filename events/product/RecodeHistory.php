<?php

namespace app\shop\events\product;

use app\shop\model\api\HistoryModel;

class RecodeHistory
{
    public function handle($payload)
    {
        $user = $payload->user;
        $product = $payload->product;
        if (!empty($user)) {
            $history = HistoryModel::where('user_id', $user->id)->where('product_id', $product->id)->first();
            if (empty($history)) {
                (new HistoryModel)->save([
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);
            } else {
                $history->updated_at = time();
                $history->save();
            }
        }
    }
}
