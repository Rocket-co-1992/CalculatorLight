<?php
namespace Controllers;

use Core\Template\TemplateManager;
use Core\Request;

class TemplateController {
    private $templateManager;
    private $request;

    public function __construct(Request $request) {
        $this->templateManager = new TemplateManager();
        $this->request = $request;
    }

    public function getTemplates() {
        $productId = $this->request->getParam('product_id');
        return [
            'template' => 'templates/list.twig',
            'data' => [
                'templates' => $this->templateManager->getTemplates($productId)
            ]
        ];
    }

    public function saveTemplate() {
        if (!$this->request->isAjax()) {
            return ['error' => 'Invalid request'];
        }

        $data = $this->request->getJsonBody();
        $templateId = $this->templateManager->createTemplate($data);
        
        return [
            'success' => true,
            'template_id' => $templateId
        ];
    }
}
