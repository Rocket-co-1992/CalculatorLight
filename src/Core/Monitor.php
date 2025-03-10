<?php
namespace Core;

class Monitor {
    private $db;
    private $printerManager;
    private $wsServer;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->printerManager = new PrinterManager();
        $this->wsServer = new \Core\WebSocket\Server();
        $eventDispatcher = Event\EventDispatcher::getInstance();
        $eventDispatcher->addListener('order.processing.start', [$this, 'onOrderProcessingStart']);
        $eventDispatcher->addListener('order.processing.file_error', [$this, 'onOrderProcessingError']);
    }

    public function getSystemStatus() {
        return [
            'printers' => $this->printerManager->getAvailablePrinters(),
            'queue' => $this->getQueueStatus(),
            'jobs' => $this->getActiveJobs(),
            'alerts' => $this->getActiveAlerts(),
            'schedule_efficiency' => $this->calculateScheduleEfficiency()
        ];
    }

    private function getQueueStatus() {
        return $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM production_queue 
            GROUP BY status"
        )->fetchAll();
    }

    private function getActiveJobs() {
        return $this->db->query("
            SELECT pq.*, o.id as order_id, p.name as printer_name
            FROM production_queue pq
            JOIN orders o ON pq.order_id = o.id
            JOIN printers p ON pq.printer_id = p.id
            WHERE pq.status = 'printing'"
        )->fetchAll();
    }

    private function calculateScheduleEfficiency() {
        $stats = $this->db->query("
            SELECT 
                AVG(TIMESTAMPDIFF(MINUTE, scheduled_start, actual_start)) as avg_delay,
                COUNT(CASE WHEN actual_start > scheduled_start THEN 1 END) as delayed_jobs,
                COUNT(*) as total_jobs
            FROM production_schedule
            WHERE status = 'completed'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
        )->fetch();

        return [
            'efficiency_score' => $this->calculateEfficiencyScore($stats),
            'avg_delay' => $stats['avg_delay'],
            'on_time_percentage' => ($stats['total_jobs'] - $stats['delayed_jobs']) / $stats['total_jobs'] * 100
        ];
    }

    public function monitorPrinters() {
        $printers = $this->printerManager->getAvailablePrinters();
        foreach ($printers as $printer) {
            $status = $this->checkPrinterStatus($printer);
            if ($status['hasChanges']) {
                $this->wsServer->broadcast([
                    'printer_id' => $printer['id'],
                    'status' => $status
                ], 'printer_update');
            }
        }
    }

    private function checkPrinterStatus($printer) {
        // Implementation to check printer status
        return ['hasChanges' => false];
    }

    public function monitorPrinterStatus() {
        $printers = $this->printerManager->getAvailablePrinters();
        foreach ($printers as $printer) {
            $status = $this->checkPrinterStatus($printer);
            if ($status['hasChanges']) {
                $this->notifyStatusChange('printer', [
                    'id' => $printer['id'],
                    'status' => $status['current'],
                    'queue' => $this->getQueueStatus($printer['id'])
                ]);
            }
        }
    }

    private function notifyStatusChange($type, $data) {
        $this->wsServer->broadcast(json_encode([
            'type' => $type,
            'data' => $data,
            'timestamp' => time()
        ]));
    }

    public function onOrderProcessingStart($data) {
        $this->wsServer->broadcast([
            'type' => 'order_status',
            'status' => 'processing',
            'order_id' => $data['order_id']
        ], 'status_update');
    }

    public function onOrderProcessingError($data) {
        $this->wsServer->broadcast([
            'type' => 'order_error',
            'order_id' => $data['order_id'],
            'errors' => $data['errors']
        ], 'status_update');
    }
}