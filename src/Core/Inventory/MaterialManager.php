<?php
namespace Core\Inventory;

class MaterialManager {
    private $db;
    private $mailer;
    private $logger;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->mailer = new \Core\Mailer();
        $this->logger = new \Core\Logger();
    }

    public function checkReorderLevels() {
        $sql = "SELECT * FROM material_inventory 
                WHERE quantity_available <= reorder_point";
        $materials = $this->db->query($sql)->fetchAll();

        foreach ($materials as $material) {
            $this->createPurchaseOrder($material);
        }
    }

    private function createPurchaseOrder($material) {
        try {
            $this->db->beginTransaction();
            
            $orderId = $this->insertPurchaseOrder($material);
            $this->notifyPurchasing($orderId, $material);
            
            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->logger->logError($e);
            throw $e;
        }
    }
}
