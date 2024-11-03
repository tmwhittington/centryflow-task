<?php

namespace App\Api;

use App\Services\InventoryManager;

class StockApi
{

    private $inventoryManager;
    public function __construct() {
        $this->inventoryManager = new InventoryManager();
    }

    public function check(string $productId) {
        // TODO: Implement
    }

    public function update(string $productId, int $newStockLevel) {
        // TODO: Implement
    }


}