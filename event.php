<?php

return [
    'listen' => [
        'ShopViewProduct' => [
            "app\\shop\\events\\product\\RecodeHistory"
        ],
        'ShopHotWords' => [
            "app\\shop\\events\\index\\HotWords"
        ],
        'ShopAfterUserComment' => [
            "app\\shop\\events\\user\\UpdateProductComment"
        ],
        'ShopGetWechatUserinfo' => [
            "app\\shop\\events\\user\\DownWechatAvatar"
        ],
        'ShopAfterCalcPrice' => [
            "app\\shop\\events\\order\\UseMoneyScore"
        ],
        'ShopAfterOrderCreated' => [
            "app\\shop\\events\\order\\OrderCreateAfter", "app\\shop\\events\\order\\UseScore", "app\\shop\\events\\order\\UseMoney", "app\\shop\\events\\order\\RecordCreateLog"
        ],
        'ShopAfterOrderReceived' => [
            "app\\shop\\events\\order\\RecordReceivedLog"
        ],
        'ShopAfterCancelOrder' => [
            "app\\shop\\events\\order\\AfterCancel"
        ],
        'ShopOrderPayOk' => [
            "app\\shop\\events\\order\\PayOk", "app\\shop\\events\\order\\RecordPayLog"
        ],
        'ShopOrderRefund' => [
            "app\\shop\\events\\order\\Refund", "app\\shop\\events\\order\\RecordRefundLog"
        ],
        'ShopOrderApplyRefund' => [
            "app\\shop\\events\\order\\RecordApplyRefundLog"
        ],
        'ShopOrderCancelPostsale' => [
            "app\\shop\\events\\order\\RecordCancelPostSaleLog"
        ],
        'ShopOrderExchange' => [
            "app\\shop\\events\\order\\RecordOrderExchangeLog"
        ],
        'ShopOrderShip' => [
            "app\\shop\\events\\order\\AutoReceive", "app\\shop\\events\\order\\RecordShipLog"
        ],
        'ShopOrderReject' => [
            "app\\shop\\events\\order\\RecordRejectLog"
        ],
        'ShopUpdateOrderAddress' => [
            "app\\shop\\events\\order\\RecordOrderAddressLog"
        ],
        'ShopUpdateOrderContactorName' => [
            "app\\shop\\events\\order\\RecordOrderContactorNameLog"
        ],
        'ShopUpdateOrderContactorPhone' => [
            "app\\shop\\events\\order\\RecordOrderContactorPhoneLog"
        ]
    ],
    'subscribe' => [
        'app\shop\subscribe\Event',
        'app\shop\subscribe\EsSearch'
    ]
];