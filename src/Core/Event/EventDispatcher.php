<?php
namespace Core\Event;

class EventDispatcher {
    private static $instance = null;
    private $listeners = [];
    private $logger;

    private function __construct() {
        $this->logger = new \Core\Logger();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function dispatch($eventName, $data = []) {
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                $listener($data);
            }
        }
        
        $this->logger->logActivity(null, "event.$eventName", $data);
    }

    public function addListener($eventName, callable $listener) {
        $this->listeners[$eventName][] = $listener;
    }
}
