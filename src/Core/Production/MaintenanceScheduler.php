<?php
namespace Core\Production;

class MaintenanceScheduler {
    private $db;
    private $printerMonitor;
    private $notificationManager;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->printerMonitor = new PrinterMonitor();
        $this->notificationManager = new \Core\Notification\NotificationManager();
    }

    public function scheduleMaintenanceTasks() {
        $printers = $this->getActivePrinters();
        foreach ($printers as $printer) {
            $this->analyzeMaintenanceNeeds($printer);
        }
    }

    private function analyzeMaintenanceNeeds($printer) {
        $metrics = $this->printerMonitor->monitorPrinterHealth($printer['id']);
        $maintenanceTask = $this->determineMaintenanceTask($metrics);
        
        if ($maintenanceTask) {
            $this->scheduleMaintenance($printer['id'], $maintenanceTask);
        }
    }
}
