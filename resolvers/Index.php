<?php

namespace app\shop\resolvers;

use app\shop\logic\api\IndexLogic;

class Index 
{
    public function sendMessage($fieldValue)
    {
        $to = $fieldValue->getArg('uid');
        $message = $fieldValue->getArg('message');
        $pusher = ev('MessagePusher');
        $channel = 'private-MESSAGECENTER-' . $to;
        $pusher->trigger($channel, 'shop-message', [
            'message' => $message
        ]);
    }

    public function getPage($fieldValue)
    {
        return IndexLogic::instance()->getPage($fieldValue->getArg('id'));
    }

    public function getHotWords($fieldValue)
    {
        $payload = (object)['data' => [], 'params' => $fieldValue->getArgs() ];
        event('ShopHotWords', $payload);
        return $payload->data;
    }
}