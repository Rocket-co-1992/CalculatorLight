<?php
namespace Controllers;

class ReportController {
    private $reportGenerator;

    public function __construct() {
        $this->reportGenerator = new \Core\Analytics\ReportGenerator();
    }

    public function generateReport($type) {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        switch ($type) {
            case 'sales':
                return $this->reportGenerator->generateSalesReport($startDate, $endDate);
            case 'production':
                return $this->reportGenerator->generateProductionReport($startDate, $endDate);
            default:
                throw new \Exception('Invalid report type');
        }
    }

    public function exportReport($type, $format) {
        $report = $this->generateReport($type);
        switch ($format) {
            case 'pdf':
                return $this->exportToPDF($report);
            case 'csv':
                return $this->exportToCSV($report);
            default:
                throw new \Exception('Invalid export format');
        }
    }
}
