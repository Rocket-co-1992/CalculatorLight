<?php
namespace Controllers;

class QualityControlController {
    private $request;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
    }
    
    public function checkPreflightStatus($orderId) {
        $preflight = new \Services\PreflightService();
        $result = $preflight->check($orderId);
        
        return $this->jsonResponse([
            'success' => true,
            'order_id' => $orderId,
            'status' => $result['status'],
            'issues' => $result['issues']
        ]);
    }
    
    public function approveProduction($orderId) {
        $qc = new \Models\QualityControl();
        $result = $qc->approveOrder($orderId, [
            'approved_by' => $this->request->getUserId(),
            'notes' => $this->request->getParam('notes')
        ]);
        
        return $this->jsonResponse(['success' => $result]);
    }
}
