<?php

namespace App\Mappers;

class ProductsMapper
{

    public function map(array $jsonRecords) {

        $list = [];

        foreach($jsonRecords as $record) {
            $product = new \App\Models\Product();
            $product->setId($record['id']);
            $product->setName($record['name']);
            $product->setDescription($record['description'] ?? null);
            $product->setPrice($record['price']);
            $product->setImage($record['image'] ?? null);
            $product->setStockLevel($record['stock_level']);
            $list[] = $product;
        }

        return $list;
    }
}