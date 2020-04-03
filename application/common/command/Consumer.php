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
        $this->setName('consume')
            ->setDescription('消费队列');
    }

    protected function execute(Input $input, Output $output)
    {
        $mq = RabbitMQ::getInstance();
        $mq->receiveMessage('test', 'test', 'test_test',
            function($message) use ($output) {
                //no topic
                if (!isset($message['topic'])) {
                    $output->writeln('invalid message: has no topic! ^_^ ,content:' . json_encode($message));
                    return;
                }

                $arr = explode('.', $message['topic']);

                //invalid topic
                if (!isset($arr[0]) || !isset($arr[1]) || !isset($arr[2])) {
                    $output->writeln('topic is invalid! ^_^');
                    return;
                }

                $module = $arr[0];
                $class = $arr[1];
                $method = $arr[2];
                $message_class_name = '\app\message' . '\\' . $module . '\\' . $class;

                $obj = null;
                if (class_exists($message_class_name)) {
                    $obj = new $message_class_name;
                } else {
                    $output->writeln($message_class_name . ' not found! ^_^');
                    return;
                }

                $retry_times = isset($message['retry_times']) ? $message['retry_times'] : 3;
                $retry_exception_list = isset($message['retry_exception_list']) ? $message['retry_exception_list'] : ['app\exception\TimeoutException'];

                if (method_exists($obj, $method)) {
                    try {
                        $result = $obj->$method($message);
                        if (is_string($result)) {
                            $output->writeln($result);
                        } else if (is_array($result) || is_object($result)) {
                            $output->writeln(json_encode($result));
                        }
                    } catch (\Exception $e) {
                        $output->writeln($e->getMessage());
                        if ($retry_times > 0 && in_array(get_class($e), $retry_exception_list)) {
                            for ($i = 0; $i < $retry_times; $i++) {
                                try {
                                    $result = $obj->$method($message);
                                    if (is_string($result)) {
                                        $output->writeln($result);
                                    } else if (is_array($result) || is_object($result)) {
                                        $output->writeln(json_encode($result));
                                    }
                                    echo 'retry ' . ($i + 1) . ' : ' . 'success' . PHP_EOL;
                                    return true;
                                } catch (\Exception $e) {
                                    echo 'retry ' . ($i + 1) . ' : ' . $e->getMessage() . PHP_EOL;
                                }
                            }
                        }
                    }
                } else {
                    $output->writeln($method . ' not found! ^_^');
                }
        });
    }
}