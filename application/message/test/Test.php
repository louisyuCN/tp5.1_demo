<?php


namespace app\message\test;


use app\model\MqConsumers;

class Test
{
    public function getMessage($message)
    {
        MqConsumers::create([
            'topic' => $message['topic'],
            'nos' => $message['nos']
        ]);
        return '保存成功!';
    }
}