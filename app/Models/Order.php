<?php

namespace App\Models;



class Order
{

    private string $id;

    private string $product_id;

    private int $quantity;

    private int $timestamp;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getProductId(): string
    {
        return $this->product_id;
    }

    public function setProductId(string $product_id): Order
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): Order
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): Order
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'timestamp' => $this->timestamp
        ];
    }





}