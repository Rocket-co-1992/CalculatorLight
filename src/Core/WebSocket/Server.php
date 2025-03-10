<?php
namespace Core\WebSocket;

class Server {
    private $clients = [];
    private $port;

    public function __construct($port = 8080) {
        $this->port = $port;
    }

    public function start() {
        $server = new \Ratchet\App('localhost', $this->port);
        $server->route('/printer', new PrinterStatusHandler());
        $server->route('/notifications', new NotificationHandler());
        $server->run();
    }

    public function broadcast($message, $type) {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => $type,
                'data' => $message
            ]));
        }
    }
}
