<?php
namespace Models;

class Template {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function getAll($productId = null) {
        $sql = "SELECT * FROM design_templates";
        if ($productId) {
            $sql .= " WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['product_id' => $productId]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function save($data) {
        $sql = "INSERT INTO design_templates (name, product_id, design_data, thumbnail) 
                VALUES (:name, :product_id, :design_data, :thumbnail)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
