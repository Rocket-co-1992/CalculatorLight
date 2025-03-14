<?php
namespace Services;

class ShippingService {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function calculateShipping($order, $address) {
        // Calculate package dimensions
        $dimensions = $this->calculatePackageDimensions($order['items']);
        
        // Get available carriers
        $carriers = $this->getAvailableCarriers($address['country']);
        
        $rates = [];
        foreach ($carriers as $carrier) {
            $rate = $this->calculateCarrierRate(
                $carrier,
                $dimensions,
                $address
            );
            
            if ($rate) {
                $rates[$carrier['id']] = [
                    'name' => $carrier['name'],
                    'price' => $rate['price'],
                    'estimated_days' => $rate['delivery_time']
                ];
            }
        }
        
        return $rates;
    }

    private function calculatePackageDimensions($items) {
        $totalWeight = 0;
        $totalVolume = 0;
        
        foreach ($items as $item) {
            $specs = $this->getProductSpecs($item['product_id']);
            $totalWeight += $specs['weight'] * $item['quantity'];
            $totalVolume += ($specs['width'] * $specs['height'] * $specs['depth']) * $item['quantity'];
        }
        
        return [
            'weight' => $totalWeight,
            'volume' => $totalVolume
        ];
    }
}
