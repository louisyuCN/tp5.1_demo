<?php


namespace app\message\test;


use app\exception\TimeoutException;
use app\model\MqConsumers;

class Test
{
    public function getMessage($message)
    {
        MqConsumers::create([
            'topic' => $message['topic'],
            'nos' => $message['nos']
        ]);
        throw new \Exception('test error');
        return '保存成功!';
    }
}