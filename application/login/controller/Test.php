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

    public function test3()
    {
        $result = '111';
        $mq = RabbitMQTool::getInstance();
        $mq->receiveMessage('test', 'test', function ($message) use ($result) {
            $result = $message;
            var_dump('$message');
        });
//        while ($result === null) {
//            if ($result !== null)
//                return $result;
//        }
    }



}