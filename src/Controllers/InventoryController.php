<?php
namespace Controllers;

class InventoryController {
    private $request;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
    }
    
    public function index() {
        $inventory = new \Models\Inventory();
        return [
            'template' => 'inventory/index.twig',
            'data' => [
                'materials' => $inventory->getAllMaterials(),
                'low_stock' => $inventory->getLowStockMaterials(),
                'stats' => $this->getInventoryStats()
            ]
        ];
    }
}
