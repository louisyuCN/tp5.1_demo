<?php


namespace app\common;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQTool
{
    private static $instance;
    private static function getConnection()
    {
        try {
            $connection = AMQPStreamConnection::create_connection([
                ['host' => '192.168.18.251', 'port' => '5672', 'user' => 'guest', 'password' => 'guest', 'vhost' => '/']
            ], []);

            register_shutdown_function(function () use($connection) {
                $connection->close();
            });

            echo 'mq连接成功!';
            return $connection;
        } catch (\Exception $e) {
            die(self::doEncoding($e->getMessage()));
        }
    }

    private function __construct()
    {
    }

    public static function getInstance()
    {
        $instance = self::$instance;
        if ($instance == null) {
            self::$instance = new RabbitMQTool();
        }
        return self::$instance;
    }

    private static function getProcessHandler($handle)
    {
        return function ($message) use ($handle)
        {
            call_user_func($handle, $message);
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

    }

    public function receiveMessage($exchange, $queue, $routing_key, $handle)
    {
        try
        {
            $consumerTag = 'consumer';
            $channel = self::getConnection()->channel();
            $channel->queue_declare($queue, false, true, false, false);
            $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
            $channel->queue_bind($queue, $exchange);

            $handler = self::getProcessHandler($handle);
            $channel->basic_consume($queue, $consumerTag, false, false, false, false, $handler);

            while ($channel->is_consuming()) {
                $channel->wait();
            }
        }
        catch(\Exception $e)
        {
            die(self::doEncoding($e->getMessage()));
        }
        finally
        {
            $channel->close();
        }
    }

    public function sendMessage($exchange, $queue, String $body)
    {
        try
        {
            $channel = self::getConnection()->channel();
            $channel->queue_declare($queue, false, true, false, false);
            $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
            $channel->queue_bind($queue, $exchange);
            $message = new AMQPMessage($body, [ 'content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT ]);
            $channel->basic_publish($message, $exchange);

        } catch (\Exception $e)
        {
            die(self::doEncoding($e->getMessage()));
        }
        finally
        {
            $channel->close();
        }
    }

    public static function doEncoding($str){
        $encode = strtoupper(mb_detect_encoding($str, ["ASCII",'UTF-8',"GB2312","GBK",'BIG5']));
        if($encode!='UTF-8'){
            $str = mb_convert_encoding($str, 'UTF-8', $encode);
        }
        return $str;
    }

}

