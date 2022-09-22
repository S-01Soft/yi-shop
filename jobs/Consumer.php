<?php

namespace app\shop\jobs;

class Consumer implements \app\queue\Consumer 
{
    public $queue = '';

    public $connection = 'default';

    public function consume($param)
    {}

}