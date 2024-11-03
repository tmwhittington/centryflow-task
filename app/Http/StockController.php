<?php

namespace App\Http;

require './View/View.php';
require './Services/InventoryManager.php';
require './Util/JsonResponse.php';

use App\Services\InventoryManager;
use App\Util\JsonResponse;
use app\View\View;

class StockController
{

    public function lookupForm() {

        $inventoryManager = new InventoryManager();
        $products = $inventoryManager->getProducts();
        $orders = $inventoryManager->getOrders();


        (new View("../resources/views/modules/stock/index.php", [
            'products' => $products,
            'orders' => $orders,
            'im' => $inventoryManager,
            'pageTitle' => 'Stock Lookup'
        ]))->render();
    }

    public function checkStock() {

        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        $productId = $data['product_id'];

       $remaining =  (new InventoryManager())->getStockCountForProduct($productId);

       if ($remaining < 5) {
           return (new JsonResponse([
               'message' => 'Stock Low',
               'detail' => sprintf("There are only %s units of this product remaining", $remaining)
           ], 422))->send();
       }

        return (new JsonResponse([
            'message' => 'In Stock',
            'detail' => sprintf("There are %s units of this product in stock", $remaining)
        ], 200))->send();
    }

    public function processOrder() {
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        $productId = $data['product_id'];
        $quantity = $data['quantity'];

        $remaining =  (new InventoryManager())->getStockCountForProduct($productId);

        if ($remaining < $quantity) {
            return (new JsonResponse([
                'message' => 'Unable to fulfil request',
                'detail' => sprintf("We could not fulfil your request for %s of this item as only %s are remaining", $quantity, $remaining)
            ], 422))->send();
        }
        $inventoryManager = new InventoryManager();

        try {
            $inventoryManager->processOrder($productId, $quantity);

            return (new JsonResponse([
                'message' => 'Order processed',
                'detail' => ""
            ], 200))->send();

        } catch (\Exception $e) {
            return (new JsonResponse([
                'message' => sprintf("An error occurred: %s", $e->getMessage()),
                'detail' => ""
            ], 500))->send();
        }






    }
}