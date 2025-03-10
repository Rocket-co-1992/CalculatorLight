<?php
namespace Controllers;

use Core\Request;
use Models\Product;

class ProductController {
    private $product;
    private $request;

    public function __construct(Request $request) {
        $this->product = new Product();
        $this->request = $request;
    }

    public function index() {
        $products = $this->product->getAll();
        return ['template' => 'products/index.twig', 'data' => ['products' => $products]];
    }

    public function editor($id) {
        $product = $this->product->getById($id);
        return [
            'template' => 'products/editor.twig',
            'data' => [
                'product' => $product,
                'editorConfig' => $this->getEditorConfig($product)
            ]
        ];
    }

    public function calculatePrice() {
        if (!$this->request->isAjax()) {
            return ['error' => 'Invalid request'];
        }

        $productId = $this->request->getParam('product_id');
        $options = $this->request->getParam('options', []);
        
        $price = $this->product->calculatePrice($productId, $options);
        return ['price' => $price];
    }

    private function getEditorConfig($product) {
        return [
            'canvas' => [
                'width' => $product['width'],
                'height' => $product['height'],
                'dpi' => 300
            ],
            'tools' => [
                'text' => true,
                'image' => true,
                'shapes' => true,
                'draw' => true
            ]
        ];
    }
}
