<?php
namespace Core\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class EventHandler implements MessageComponentInterface {
    protected $clients;
    protected $subscriptions;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        switch ($data['type']) {
            case 'subscribe':
                $this->handleSubscribe($from, $data['channel']);
                break;
            case 'status_update':
                $this->broadcastStatus($data['data']);
                break;
        }
    }

    private function handleSubscribe($client, $channel) {
        if (!isset($this->subscriptions[$channel])) {
            $this->subscriptions[$channel] = new \SplObjectStorage;
        }
        $this->subscriptions[$channel]->attach($client);
    }
}
