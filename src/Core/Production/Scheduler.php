<?php
namespace Core\Production;

class Scheduler {
    private $db;
    private $workflow;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->workflow = new Workflow();
    }

    public function scheduleJobs() {
        $pendingJobs = $this->getPendingJobs();
        $schedule = $this->optimizeSchedule($pendingJobs);
        
        foreach ($schedule as $job) {
            $this->assignJobToMachine($job);
        }
    }

    private function optimizeSchedule($jobs) {
        $schedule = [];
        $printers = $this->getPrinterAvailability();
        
        foreach ($jobs as $job) {
            $optimalSlot = $this->findOptimalTimeSlot($job, $printers);
            if ($optimalSlot) {
                $schedule[] = [
                    'job' => $job,
                    'printer_id' => $optimalSlot['printer_id'],
                    'start_time' => $optimalSlot['start_time']
                ];
            }
        }
        
        return $this->balanceWorkload($schedule);
    }

    private function findOptimalTimeSlot($job, $printers) {
        $bestSlot = null;
        $minSetupTime = PHP_INT_MAX;

        foreach ($printers as $printer) {
            if (!$this->canPrintJob($printer, $job)) {
                continue;
            }

            $setupTime = $this->calculateSetupTime($printer['last_job'], $job);
            if ($setupTime < $minSetupTime) {
                $bestSlot = [
                    'printer_id' => $printer['id'],
                    'start_time' => $this->getEarliestAvailableTime($printer)
                ];
                $minSetupTime = $setupTime;
            }
        }

        return $bestSlot;
    }

    private function balanceWorkload($schedule) {
        usort($schedule, function($a, $b) {
            return $this->calculatePriority($a['job']) - $this->calculatePriority($b['job']);
        });
        return $schedule;
    }
}
