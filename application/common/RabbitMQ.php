<?php


namespace app\common;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $connection = AMQPStreamConnection::create_connection([
            [
                'host' => config('rabbitmq.host'),
                'port' => config('rabbitmq.port'),
                'user' => config('rabbitmq.username'),
                'password' => config('rabbitmq.password'),
                'vhost' => config('rabbitmq.vhost')
            ]
        ], []);

        register_shutdown_function(function () use($connection) {
            $connection->close();
        });

        $this->connection = $connection;
    }

    public static function getInstance()
    {
        $instance = self::$instance;
        if ($instance == null) {
            self::$instance = new RabbitMQ();
        }
        return self::$instance;
    }

    private static function getProcessHandler(callable $handle)
    {
        return function ($message) use ($handle)
        {
            self::ackMessage($message, $handle);
        };
    }

    private static function ackMessage($message, $handle)
    {
        call_user_func($handle, json_decode($message->body, true));
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }

    public function receiveMessage(string $exchange, string $queue, string $routing_key, callable $handle)
    {
        try {
            $consumerTag = 'consumer';
            $channel = $this->connection->channel();
            $channel->queue_declare($queue, false, true, false, false);
            $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
            $channel->queue_bind($queue, $exchange, $routing_key);

            $handler = self::getProcessHandler($handle);
            $channel->basic_consume($queue, $consumerTag, false, false, false, false, $handler);

            while ($channel->is_consuming()) {
                $channel->wait();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        } finally {
            $channel->close();
        }
    }

    public function sendMessage(string $exchange, string $queue, string $routing_key, array $body)
    {
        try {
            $channel = $this->connection->channel();
            $channel->queue_declare($queue, false, true, false, false);
            $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
            $channel->queue_bind($queue, $exchange, $routing_key);
            $message = new AMQPMessage(json_encode($body), [ 'content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT ]);
            $channel->basic_publish($message, $exchange);
        } catch (\Exception $e) {
            echo $e->getMessage();
        } finally {
            $channel->close();
        }
    }
//    public static function doEncoding(string $str){
//        $encode = strtoupper(mb_detect_encoding($str, ["ASCII",'UTF-8',"GB2312","GBK",'BIG5']));
//        if($encode!='UTF-8'){
//            $str = mb_convert_encoding($str, 'UTF-8', $encode);
//        }
//        return $str;
//    }

}

