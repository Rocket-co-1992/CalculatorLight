<?php
namespace Controllers;

class DesignEditorController {
    private $request;
    private $fileManager;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
        $this->fileManager = new \Services\FileManager();
    }
    
    public function editor($productId = null) {
        $product = new \Models\Product();
        $templates = $product->getTemplates($productId);
        
        return [
            'template' => 'design/editor.twig',
            'data' => [
                'product' => $product->getById($productId),
                'templates' => $templates,
                'fonts' => $this->getAvailableFonts(),
                'print_specs' => $product->getPrintSpecs($productId)
            ]
        ];
    }
    
    public function loadTemplate($templateId) {
        $template = new \Models\Template();
        $data = $template->getById($templateId);
        
        return $this->jsonResponse([
            'success' => true,
            'template' => $data
        ]);
    }
    
    public function saveDesign() {
        $designData = $this->request->getParam('design');
        
        // Validate design data
        if (!$this->validateDesignData($designData)) {
            throw new \Exception('Invalid design data');
        }
        
        // Generate preview
        $preview = $this->generatePreview($designData);
        
        // Save design
        $design = new \Models\Design();
        $result = $design->save([
            'user_id' => $this->request->getUserId(),
            'product_id' => $designData['product_id'],
            'data' => json_encode($designData['elements']),
            'preview_url' => $preview,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->jsonResponse([
            'success' => true,
            'design_id' => $result
        ]);
    }
    
    private function validateDesignData($data) {
        return isset($data['product_id']) && 
               isset($data['elements']) && 
               is_array($data['elements']);
    }
    
    private function generatePreview($designData) {
        $renderer = new \Services\DesignRenderer();
        return $renderer->generatePreview($designData);
    }
    
    private function getAvailableFonts() {
        return [
            ['id' => 1, 'name' => 'Arial', 'url' => '/fonts/arial.ttf'],
            ['id' => 2, 'name' => 'Helvetica', 'url' => '/fonts/helvetica.ttf'],
            ['id' => 3, 'name' => 'Times New Roman', 'url' => '/fonts/times.ttf']
        ];
    }
    
    private function jsonResponse($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
