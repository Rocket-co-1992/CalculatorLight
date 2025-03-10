<?php
namespace Core\API;

class ProductionEndpoint {
    private $workflow;
    private $monitor;

    public function __construct() {
        $this->workflow = new \Core\Production\Workflow();
        $this->monitor = new \Core\Production\Monitor();
    }

    public function handle() {
        $request = new \Core\Request();
        $action = $request->getParam('action');

        switch ($action) {
            case 'status':
                return $this->getProductionStatus();
            case 'schedule':
                return $this->updateSchedule($request->getJsonBody());
            case 'batch':
                return $this->processBatch($request->getParam('batch_id'));
            default:
                throw new \Exception('Invalid action', 400);
        }
    }

    private function getProductionStatus() {
        return $this->monitor->getSystemStatus();
    }
}
