<?php
namespace Core\Production;

class CapacityPlanner {
    private $db;
    private $scheduler;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->scheduler = new Scheduler();
    }

    public function calculateAvailableCapacity($startDate, $endDate) {
        return [
            'total_hours' => $this->getTotalWorkingHours($startDate, $endDate),
            'scheduled_hours' => $this->getScheduledHours($startDate, $endDate),
            'maintenance_hours' => $this->getMaintenanceHours($startDate, $endDate),
            'available_hours' => $this->getAvailableHours($startDate, $endDate)
        ];
    }

    public function optimizeSchedule($jobs) {
        $capacity = $this->calculateAvailableCapacity(date('Y-m-d'), date('Y-m-d', strtotime('+7 days')));
        $optimizedJobs = $this->prioritizeJobs($jobs, $capacity);
        
        return $this->scheduler->createSchedule($optimizedJobs);
    }

    private function prioritizeJobs($jobs, $capacity) {
        usort($jobs, function($a, $b) {
            // Sort by priority, deadline, and setup optimization
            return $this->calculateJobPriority($b) - $this->calculateJobPriority($a);
        });
        return $jobs;
    }
}
