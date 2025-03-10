<?php
namespace Models;

class Cart {
    private $items = [];
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->loadCart();
    }

    public function addItem($productId, $quantity, $designData) {
        $product = (new Product())->getById($productId);
        if (!$product) return false;

        $itemId = uniqid('item_');
        $this->items[$itemId] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product['base_price'],
            'design_data' => $designData
        ];

        $this->saveCart();
        return true;
    }

    public function getItems() {
        return array_map(function($item) {
            $product = (new Product())->getById($item['product_id']);
            return array_merge($item, ['product' => $product]);
        }, $this->items);
    }

    public function getTotal() {
        return array_reduce($this->items, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }

    private function loadCart() {
        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        }
    }

    private function saveCart() {
        $_SESSION['cart'] = $this->items;
    }
}
