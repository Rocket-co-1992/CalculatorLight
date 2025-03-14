<?php
namespace Install;

class InitialData {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function seed() {
        $this->createAdminUser();
        $this->createDefaultCategories();
        $this->createDefaultSettings();
    }

    private function createAdminUser($email, $password, $name) {
        $sql = "INSERT INTO users (email, password, name, role) 
                VALUES (:email, :password, :name, 'admin')";
                
        $this->db->prepare($sql)->execute([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $name
        ]);
    }

    private function createDefaultCategories() {
        $categories = [
            ['name' => 'Business Cards', 'slug' => 'business-cards'],
            ['name' => 'Flyers', 'slug' => 'flyers'],
            ['name' => 'Posters', 'slug' => 'posters']
        ];

        $sql = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
        $stmt = $this->db->prepare($sql);

        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    }
}
