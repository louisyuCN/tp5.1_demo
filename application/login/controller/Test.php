<?php


namespace app\login\controller;


use app\common\HttpResponse;
use app\common\RabbitMQ;
use app\model\MqConsumers;

class Test
{
    public function test()
    {
        return HttpResponse::success('test');
    }
    
    public function test2($topic='test.Test.getMessage', $nos='111')
    {
        $mq = RabbitMQ::getInstance();
        $mq->sendMessage('test', 'test', 'test_test', [
            'topic' => $topic,
            'nos' => $nos,
        ]);
    }
}