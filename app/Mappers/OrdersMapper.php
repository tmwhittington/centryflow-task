<?php

namespace App\Mappers;

use App\Models\Order;

class OrdersMapper
{

    public function map(array $jsonRecords) {

        $list = [];

        foreach ($jsonRecords as $record) {
            $order = new Order();
            $order->setId($record['id'])
                ->setProductId($record['product_id'])
                ->setQuantity($record['quantity'])
                ->setTimestamp($record['timestamp']);

            $list[] = $order;
        }

        return $list;
    }
}