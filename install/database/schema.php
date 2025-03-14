<?php
namespace Install\Database;

class Schema {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createTables() {
        $tables = [
            $this->createUsersTable(),
            $this->createProductsTable(),
            $this->createOrdersTable(),
            $this->createDesignsTable(),
            $this->createMaterialsTable()
        ];

        foreach ($tables as $sql) {
            $this->db->exec($sql);
        }
    }

    private function createUsersTable() {
        return "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            role ENUM('admin', 'customer', 'operator') NOT NULL,
            active BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
    }

    private function createProductsTable() {
        return "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            base_price DECIMAL(10,2) NOT NULL,
            category_id INT,
            active BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
    }
}
