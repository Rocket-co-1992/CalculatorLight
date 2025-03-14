<?php
namespace Services;

class ShippingCalculator {
    private $db;
    private $breakdown = [];

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function calculate($orderData, $shippingAddress) {
        $weight = $this->calculateTotalWeight($orderData);
        $volume = $this->calculateTotalVolume($orderData);
        
        $carriers = $this->getAvailableCarriers($shippingAddress['country']);
        $rates = [];
        
        foreach ($carriers as $carrier) {
            $rate = $this->calculateCarrierRate(
                $carrier,
                $weight,
                $volume,
                $shippingAddress
            );
            if ($rate !== false) {
                $rates[$carrier['id']] = $rate;
            }
        }
        
        $this->breakdown['weight'] = $weight;
        $this->breakdown['volume'] = $volume;
        $this->breakdown['carriers'] = $rates;
        
        return $rates;
    }

    private function calculateTotalWeight($orderData) {
        $weight = 0;
        foreach ($orderData['items'] as $item) {
            $productSpecs = $this->getProductSpecs($item['product_id']);
            $weight += $productSpecs['weight_per_unit'] * $item['quantity'];
        }
        return $weight;
    }

    private function calculateCarrierRate($carrier, $weight, $volume, $address) {
        $baseRate = $carrier['base_rate'];
        $ratePerKg = $carrier['rate_per_kg'];
        
        $total = $baseRate + ($weight * $ratePerKg);
        
        // Apply zone-based pricing
        $zoneMultiplier = $this->getZoneMultiplier($carrier['id'], $address['country']);
        $total *= $zoneMultiplier;
        
        return $total;
    }

    public function getShippingBreakdown() {
        return $this->breakdown;
    }
}
