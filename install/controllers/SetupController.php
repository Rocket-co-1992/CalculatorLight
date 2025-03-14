<?php
namespace Install\Controllers;

class SetupController {
    private $installer;
    
    public function __construct() {
        $this->installer = new \Install\Install();
    }
    
    public function renderStep($step) {
        switch ($step) {
            case 'database':
                return $this->renderDatabaseStep();
            case 'admin':
                return $this->renderAdminStep();
            case 'settings':
                return $this->renderSettingsStep();
            default:
                return $this->renderRequirementsStep();
        }
    }
    
    public function processStep($step, $data) {
        try {
            switch ($step) {
                case 'database':
                    return $this->installer->setupDatabase($data);
                case 'admin':
                    return $this->installer->createAdminUser($data);
                case 'settings':
                    return $this->installer->saveSettings($data);
                case 'finalize':
                    return $this->installer->finalize();
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
