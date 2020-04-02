<?php


namespace app\login\controller;


use app\common\HttpResponse;
use app\common\RabbitMQ;
use think\Response;

class Test
{
    public function test()
    {
        return HttpResponse::success('test');
    }
    
    public function test2()
    {
        $mq = RabbitMQ::getInstance();
        $num = random_int(1, 1000);
        $mq->sendMessage('test', 'test', 'test_test', [
           // 'topic' => 'trades.sendLogistics',
            'nos' => '1234567',
        ]);
    }
}