<?php
namespace Core;

class DesignVersion {
    private $db;
    private $imageProcessor;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->imageProcessor = new ImageProcessor();
    }

    public function saveVersion($designId, $elements, $metadata = []) {
        $sql = "INSERT INTO design_versions (design_id, elements, metadata, created_at) 
                VALUES (:design_id, :elements, :metadata, NOW())";
                
        return $this->db->prepare($sql)->execute([
            'design_id' => $designId,
            'elements' => json_encode($elements),
            'metadata' => json_encode($metadata)
        ]);
    }

    public function generatePreview($version) {
        return $this->imageProcessor->renderDesign($version['elements']);
    }
}
