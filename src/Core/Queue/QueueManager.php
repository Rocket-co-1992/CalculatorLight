<?php
namespace Core\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager {
    private $connection;
    private $channel;
    private $logger;

    public function __construct() {
        $this->logger = new \Core\Logger();
        $this->connect();
    }

    public function publishJob($queue, $data) {
        $message = new AMQPMessage(
            json_encode($data),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        
        $this->channel->basic_publish($message, '', $queue);
        $this->logger->logActivity(null, 'queue.job_published', ['queue' => $queue]);
    }

    private function connect() {
        $config = require __DIR__ . '/../../../config/config.php';
        $this->connection = new AMQPStreamConnection(
            $config['queue']['host'],
            $config['queue']['port'],
            $config['queue']['user'],
            $config['queue']['password']
        );
        $this->channel = $this->connection->channel();
    }
}
