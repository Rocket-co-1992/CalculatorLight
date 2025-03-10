<?php
namespace Models;

class DesignProof {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function create($orderItemId, $customerNotes = '') {
        $sql = "INSERT INTO design_proofs (order_item_id, customer_notes) VALUES (:item_id, :notes)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'item_id' => $orderItemId,
            'notes' => $customerNotes
        ]);
    }

    public function updateStatus($proofId, $status, $adminNotes = '') {
        $sql = "UPDATE design_proofs SET status = :status, admin_notes = :notes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $proofId,
            'status' => $status,
            'notes' => $adminNotes
        ]);
    }
}
