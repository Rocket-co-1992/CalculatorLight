<?php
namespace Controllers;

use Core\Request;
use Models\DesignProof;

class DesignProofController {
    private $proof;
    private $request;

    public function __construct(Request $request) {
        $this->proof = new DesignProof();
        $this->request = $request;
    }

    public function approve($proofId) {
        $notes = $this->request->getParam('admin_notes', '');
        $result = $this->proof->updateStatus($proofId, 'approved', $notes);
        
        if ($result) {
            // Trigger order processing after approval
            $this->triggerOrderProcessing($proofId);
        }
        
        return ['success' => $result];
    }

    public function reject($proofId) {
        $notes = $this->request->getParam('admin_notes', '');
        $result = $this->proof->updateStatus($proofId, 'rejected', $notes);
        return ['success' => $result];
    }

    private function triggerOrderProcessing($proofId) {
        // Implement order processing logic
    }
}
