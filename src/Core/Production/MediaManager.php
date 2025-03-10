<?php
namespace Core\Production;

class MediaManager {
    private $db;
    private $colorManager;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->colorManager = new ColorManager();
    }

    public function createMediaProfile($data) {
        $sql = "INSERT INTO media_profiles 
                (name, type, specs, color_profile, printer_settings) 
                VALUES (:name, :type, :specs, :color_profile, :settings)";
                
        $this->db->prepare($sql)->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'specs' => json_encode($data['specs']),
            'color_profile' => $data['color_profile'],
            'settings' => json_encode($data['printer_settings'])
        ]);

        return $this->db->lastInsertId();
    }

    public function validateMediaCompatibility($mediaId, $printerId) {
        $profile = $this->getMediaProfile($mediaId);
        $printer = $this->getPrinterCapabilities($printerId);
        
        return $this->checkCompatibility($profile, $printer);
    }
}
