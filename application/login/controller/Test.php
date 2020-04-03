<?php


namespace app\login\controller;


use app\common\HttpResponse;
use app\common\RabbitMQ;

class Test
{
    public function test()
    {
        return HttpResponse::success('test');
    }
    
    public function test2($topic=null)
    {
        $mq = RabbitMQ::getInstance();
        $num = random_int(1, 1000);
        $mq->sendMessage('test', 'test', 'test_test', [
            'topic' => $topic,
            'nos' => '1234567',
        ]);
    }
}