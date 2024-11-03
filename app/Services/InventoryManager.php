<?php

namespace App\Services;

require 'FileManager.php';
require './Mappers/ProductsMapper.php';
require './Mappers/OrdersMapper.php';
require './Models/Product.php';
require './Models/Order.php';
require './Util/Uuid.php';
require './Services/NotificationService.php';

use App\Mappers\OrdersMapper;
use App\Mappers\ProductsMapper;
use App\Models\Order;
use App\Models\Product;
use App\Util\Uuid;

class InventoryManager
{

    /**
     * @var array<Product>
     */
    private array $productsList;

    private array $ordersList;


    public function __construct() {
        $this->loadOrders();
        $this->loadProducts();
    }

    public function loadProducts() {

        if(isset($_SESSION['cached_products'])) {
            $serialisedData = $_SESSION['cached_products'];
        } else {
            $serialisedData = FileManager::read(DATA_DIR . "/products.json");
            if ($serialisedData == null)
                throw new \Exception("Could not read products.json");

        }
        $data = json_decode($serialisedData, true);
        $this->productsList = (new ProductsMapper())->map($data);
        $_SESSION['cached_products'] = $serialisedData;
    }

    public function loadOrders() {

        if(isset($_SESSION['cached_orders'])) {
            $serialisedData = $_SESSION['cached_orders'];
        } else {
            $serialisedData = FileManager::read(DATA_DIR. "/orders.json");
            if($serialisedData == null)
                throw new \Exception("Could not read orders.json");
        }

        $data = json_decode($serialisedData, true);
        $this->ordersList = (new OrdersMapper())->map($data);
        $_SESSION['cached_orders'] = $serialisedData;
    }

    public function getProducts() {
        return $this->productsList;
    }

    public function getOrders() {
        return $this->ordersList;
    }

    public function getProductById($productId) {
        $matches = array_values(array_filter($this->productsList, fn($product) => $product->getId() == $productId));
        return $matches[0] ?? null;
    }

    public function getStockCountForProduct($productId) {
        $product = $this->getProductById($productId);
        return $product?->getStockLevel();
    }

    public function updateStockCountForProduct($productId, $newStockCount) {
        $product = $this->getProductById($productId);
        return $product?->setStockLevel($newStockCount);
    }
    public function processOrder($productId, $quantity) {


        $product = $this->getProductById($productId);

        if($product == null) {
            throw new \Exception("Product not found.");
        }

        if($product->getStockLevel() < $quantity) {
            throw new \Exception("Not enough products remaining to fulfil request.");
        }

        $fileContents = FileManager::read(DATA_DIR."/orders.json");
        if($fileContents == null) throw new \Exception("Could not read orders.json");

        $jsonContents = json_decode($fileContents, true);
        $ordersList = (new OrdersMapper())->map($jsonContents);

        $o = new Order();
        $o->setId(Uuid::generate(prefix: "or"))
            ->setProductId($product->getId())
            ->setQuantity($quantity)
            ->setTimestamp(time());

        $ordersList[] = $o;

        $newStockLevel = $product->getStockLevel() - $quantity;
        $product->setStockLevel($newStockLevel);

        $productsJson = array_map(fn($product) => $product->toArray(), $this->productsList);
        $ordersJson = array_map(fn($order) => $order->toArray(), $ordersList);

        FileManager::overwrite(DATA_DIR. "/orders.json", $ordersJson);
        FileManager::overwrite(DATA_DIR. "/products.json", $productsJson);

        $_SESSION['cached_orders'] = json_encode($ordersJson);
        $_SESSION['cached_products'] = json_encode($productsJson);

        if($newStockLevel < 5) {
            NotificationService::sendLowStockAlert($productId);
        }


    }

}