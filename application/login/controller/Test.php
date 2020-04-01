<?php


namespace app\login\controller;


use app\common\HttpResponse;
use app\common\RabbitMQTool;
use think\Response;

class Test
{
    public function test()
    {
        return HttpResponse::success('test');
    }
    
    public function test2()
    {
        $mq = RabbitMQTool::getInstance();
        $mq->sendMessage('test', 'test', 'HelloWorld');

    }
}