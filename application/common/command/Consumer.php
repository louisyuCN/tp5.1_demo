<?php


namespace app\common\command;


use app\common\RabbitMQ;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Consumer extends Command
{
    protected function configure()
    {
        $this->setName('consume')->setDescription('消费队列');
    }

    protected function execute(Input $input, Output $output)
    {
        $mq = RabbitMQ::getInstance();
        $mq->receiveMessage('test', 'test', 'test_test', function($message) use ($output) {
            if (!isset($message['topic'])) {
                echo '非法消息：未设置topic' . json_encode($message) . PHP_EOL;
                return;
            }
            echo json($message);
        });
    }
}