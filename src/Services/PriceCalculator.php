<?php
namespace Services;

class PriceCalculator {
    private $breakdown = [];
    
    public function calculate($productId, $quantity, $options) {
        // Add inventory validation
        $this->validateInventory($options['materials'], $quantity);

        $product = new \Models\Product();
        $productData = $product->find($productId);
        
        // Base price calculation
        $basePrice = $this->calculateBasePrice($productData, $quantity);
        $this->breakdown['base_price'] = $basePrice;
        
        // Materials cost
        $materialsCost = $this->calculateMaterialsCost($options['materials'], $quantity);
        $this->breakdown['materials'] = $materialsCost;
        
        // Finishing options
        $finishingCost = $this->calculateFinishingCost($options['finishing'], $quantity);
        $this->breakdown['finishing'] = $finishingCost;
        
        // Calculate total
        $total = $basePrice + $materialsCost + $finishingCost;
        
        return $total;
    }
    
    public function getPriceBreakdown() {
        return $this->breakdown;
    }
    
    private function calculateBasePrice($product, $quantity) {
        $basePrice = $product['base_price'];
        
        // Volume discount tiers
        if ($quantity >= 1000) {
            $basePrice *= 0.7; // 30% discount
        } elseif ($quantity >= 500) {
            $basePrice *= 0.8; // 20% discount
        } elseif ($quantity >= 100) {
            $basePrice *= 0.9; // 10% discount
        }
        
        return $basePrice * $quantity;
    }
    
    private function calculateMaterialsCost($materials, $quantity) {
        $materialsCost = 0;
        $materialRates = $this->getMaterialRates();
        
        foreach ($materials as $materialId => $specs) {
            if (isset($materialRates[$materialId])) {
                $rate = $materialRates[$materialId];
                // Apply quantity-based discounts
                if ($quantity >= $rate['min_quantity']) {
                    $rate['base_rate'] *= (1 - $rate['discount_rate']);
                }
                
                $area = ($specs['width'] * $specs['height']) / 1000000; // Convert to m²
                $materialsCost += $rate['base_rate'] * $area * $quantity;
            }
        }
        
        return $materialsCost;
    }
    
    private function calculateFinishingCost($finishing, $quantity) {
        $finishingCost = 0;
        $finishingRates = $this->getFinishingRates();
        
        foreach ($finishing as $processId => $enabled) {
            if ($enabled && isset($finishingRates[$processId])) {
                $rate = $finishingRates[$processId]['base_rate'];
                $finishingCost += $rate * $quantity;
            }
        }
        
        return $finishingCost;
    }
    
    private function getMaterialRates() {
        $material = new \Models\Material();
        return $material->getAllRates();
    }
    
    private function getFinishingRates() {
        $finishing = new \Models\Finishing();
        return $finishing->getAllRates();
    }

    private function validateInventory($materials, $quantity) {
        $inventory = new \Models\Inventory();
        foreach ($materials as $materialId => $specs) {
            $stockLevel = $inventory->checkStockLevel($materialId);
            if ($stockLevel === \Models\Inventory::STOCK_CRITICAL) {
                throw new \Exception("Material {$materialId} is out of stock");
            }
        }
        return true;
    }
}
