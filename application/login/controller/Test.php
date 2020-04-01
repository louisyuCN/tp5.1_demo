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
        $mq->sendMessage('test', 'test', 'HelloWorld');
    }
}