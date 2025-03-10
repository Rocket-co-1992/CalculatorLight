<?php
namespace Core\Template;

class TemplateManager {
    private $db;
    private $cache;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->cache = new \Core\Cache\FileCache();
    }

    public function createTemplate($data) {
        $sql = "INSERT INTO design_templates 
                (name, product_id, design_data, thumbnail) 
                VALUES (:name, :product_id, :design_data, :thumbnail)";
                
        $this->db->prepare($sql)->execute([
            'name' => $data['name'],
            'product_id' => $data['product_id'],
            'design_data' => json_encode($data['design_data']),
            'thumbnail' => $data['thumbnail']
        ]);

        return $this->db->lastInsertId();
    }

    public function renderTemplate($templateId, $data) {
        $template = $this->getTemplate($templateId);
        $renderer = new TemplateRenderer();
        return $renderer->render($template, $data);
    }
}
