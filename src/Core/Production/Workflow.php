<?php
namespace Core\Production;

use Core\Database;

class Workflow {
    private $db;
    private $orderTracker;
    private $qualityControl;
    private $fileProcessor;
    private $mlOptimizer;
    private $fileExporter;
    private $eventDispatcher;
    private $queueManager;
    private $qualityAssurance;
    private $jobEstimator;
    private $notificationManager;
    private $colorManager;
    private $printerMonitor;
    private $maintenanceScheduler;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->orderTracker = new \Core\OrderTracker();
        $this->qualityControl = new QualityControl();
        $this->fileProcessor = new \Core\Prepress\FileProcessor();
        $this->mlOptimizer = new \Core\ML\ProductionOptimizer();
        $this->fileExporter = new \Core\Export\FileExporter();
        $this->eventDispatcher = \Core\Event\EventDispatcher::getInstance();
        $this->queueManager = new \Core\Queue\QueueManager();
        $this->qualityAssurance = new \Core\QA\QualityAssurance();
        $this->jobEstimator = new JobEstimator();
        $this->notificationManager = new \Core\Notification\NotificationManager();
        $this->colorManager = new ColorManager();
        $this->printerMonitor = new PrinterMonitor();
        $this->maintenanceScheduler = new MaintenanceScheduler();
    }

    public function assignToProductionQueue($orderId) {
        $order = $this->getOrderDetails($orderId);
        $printer = $this->selectOptimalPrinter($order);
        
        return $this->db->prepare("
            INSERT INTO production_queue (order_id, printer_id, status) 
            VALUES (:order_id, :printer_id, 'queued')"
        )->execute([
            'order_id' => $orderId,
            'printer_id' => $printer['id']
        ]);
    }

    public function processBatch($batchId) {
        $batch = $this->getBatchDetails($batchId);
        
        // Add ML-based optimization
        $optimizedSchedule = $this->mlOptimizer->optimizeProductionQueue($batch);
        $this->updateBatchSchedule($batchId, $optimizedSchedule);
        
        $printer = $this->selectOptimalPrinter($batch);
        
        try {
            $this->db->beginTransaction();
            
            // Update batch status
            $this->updateBatchStatus($batchId, 'processing');
            
            // Assign to printer
            $this->assignBatchToPrinter($batchId, $printer['id']);
            
            // Update order statuses
            $this->updateBatchOrderStatuses($batchId);
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function processOrder($orderId) {
        $order = $this->getOrderDetails($orderId);
        
        $this->eventDispatcher->dispatch('order.processing.start', [
            'order_id' => $orderId,
            'timestamp' => time()
        ]);

        // Process uploaded files
        $fileResults = $this->processOrderFiles($order);
        if (!$fileResults['valid']) {
            $this->eventDispatcher->dispatch('order.processing.file_error', [
                'order_id' => $orderId,
                'errors' => $fileResults['issues']
            ]);
            return false;
        }
        
        // Perform quality checks
        $qualityResults = $this->qualityControl->checkDesignQuality($order['design_data']);
        
        if (!$this->validateQualityResults($qualityResults)) {
            $this->notifyQualityIssues($orderId, $qualityResults);
            return false;
        }

        // Run quality assurance checks
        $qaResults = $this->qualityAssurance->runQualityChecks($orderId);
        if (!$this->validateQAResults($qaResults)) {
            $this->eventDispatcher->dispatch('order.qa_failed', [
                'order_id' => $orderId,
                'qa_results' => $qaResults
            ]);
            return false;
        }

        // Add shipping optimization
        $packageOptimizer = new \Core\Shipping\PackageOptimizer();
        $shippingPlan = $packageOptimizer->optimizePackaging($orderId);
        
        if ($shippingPlan['packages']) {
            $this->updateOrderShipping($orderId, $shippingPlan);
        }

        // Generate print-ready files
        $printFiles = $this->generatePrintFiles($order);
        if (!$printFiles['success']) {
            $this->notifyFileGenerationIssues($orderId, $printFiles['errors']);
            return false;
        }

        // Add job estimation
        $estimate = $this->jobEstimator->estimateProductionTime($orderId);
        $this->updateOrderEstimate($orderId, $estimate);

        try {
            // Queue job for processing
            $jobId = $this->queueManager->publishJob('print_jobs', [
                'order_id' => $orderId,
                'files' => $printFiles['files'],
                'priority' => $this->calculateJobPriority($order),
                'estimated_time' => $estimate
            ]);

            $this->notificationManager->sendJobNotification($order['user_id'], [
                'type' => 'job_queued',
                'job_id' => $jobId,
                'estimated_completion' => $estimate['estimated_completion']
            ]);

            return true;
        } catch (\Exception $e) {
            $this->notificationManager->sendJobNotification($order['user_id'], [
                'type' => 'job_error',
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function processOrderFiles($order) {
        $issues = [];
        foreach ($order['files'] as $file) {
            try {
                $result = $this->fileProcessor->processFile($file['path']);
                if (!$this->validateFileResult($result)) {
                    $issues[] = [
                        'file' => $file['name'],
                        'problems' => $this->getFileProblems($result)
                    ];
                }
            } catch (\Exception $e) {
                $issues[] = [
                    'file' => $file['name'],
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return [
            'valid' => empty($issues),
            'issues' => $issues
        ];
    }

    private function validateQualityResults($results) {
        foreach ($results as $check) {
            if (!$check['passed']) {
                return false;
            }
        }
        return true;
    }

    private function validateQAResults($results) {
        return $results['overall_score'] >= $this->config['qa']['minimum_score'];
    }

    private function notifyQualityIssues($orderId, $issues) {
        // Implementation for quality issue notifications
    }

    private function getBatchDetails($batchId) {
        return $this->db->prepare(
            "SELECT * FROM production_batches WHERE id = ?"
        )->execute([$batchId])->fetch();
    }

    private function selectOptimalPrinter($order) {
        $availablePrinters = $this->printerManager->getAvailablePrinters();
        $healthyPrinters = array_filter($availablePrinters, function($printer) {
            $health = $this->printerMonitor->monitorPrinterHealth($printer['id']);
            return $health['status'] === 'healthy';
        });
        $colorRequirements = $this->getColorRequirements($order);
        
        foreach ($healthyPrinters as $printer) {
            if ($this->matchesColorProfile($printer, $colorRequirements)) {
                return $printer;
            }
        }
        
        return ['id' => 1]; // Fallback
    }

    private function matchesColorProfile($printer, $requirements) {
        return $this->colorManager->validateColorProfile(
            $printer['id'], 
            $requirements
        );
    }

    private function updateOrderShipping($orderId, $shippingPlan) {
        $sql = "UPDATE orders SET 
                shipping_data = :shipping_data,
                shipping_cost = :shipping_cost 
                WHERE id = :order_id";
                
        return $this->db->prepare($sql)->execute([
            'order_id' => $orderId,
            'shipping_data' => json_encode($shippingPlan['packages']),
            'shipping_cost' => $shippingPlan['shipping_cost']
        ]);
    }

    private function updateBatchSchedule($batchId, $schedule) {
        $sql = "UPDATE production_batches 
                SET schedule_data = :schedule 
                WHERE id = :id";
                
        return $this->db->prepare($sql)->execute([
            'id' => $batchId,
            'schedule' => json_encode($schedule)
        ]);
    }

    private function generatePrintFiles($order) {
        try {
            $files = [];
            foreach ($order['items'] as $item) {
                $files[] = $this->fileExporter->exportToPDF($item['design_data'], [
                    'bleed' => true,
                    'marks' => true,
                    'colorBars' => true
                ]);
            }
            return ['success' => true, 'files' => $files];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    private function updateOrderEstimate($orderId, $estimate) {
        $sql = "UPDATE orders SET 
                estimated_completion = :completion,
                estimated_duration = :duration 
                WHERE id = :order_id";
                
        $this->db->prepare($sql)->execute([
            'order_id' => $orderId,
            'completion' => $estimate['estimated_completion'],
            'duration' => $estimate['total_time']
        ]);
    }
}
