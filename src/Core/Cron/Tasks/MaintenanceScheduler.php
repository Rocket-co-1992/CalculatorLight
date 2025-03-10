<?php
namespace Core\Cron\Tasks;

class MaintenanceScheduler {
    private $printerManager;
    private $notificationManager;

    public function execute($params = []) {
        $printers = $this->printerManager->getActivePrinters();
        
        foreach ($printers as $printer) {
            $maintenanceNeeded = $this->checkMaintenanceNeeds($printer);
            if ($maintenanceNeeded) {
                $this->scheduleMaintenanceTask($printer);
            }
        }
        
        return ['scheduled_count' => count($printers)];
    }

    private function checkMaintenanceNeeds($printer) {
        return $printer['print_count'] >= $this->getMaintenanceThreshold($printer['model']);
    }
}
