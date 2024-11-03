<?php

namespace App\Models;
class Product
{

    private string $id;
    private string $name;
    private float $price;
    private string|null $image;
    private string|null $description;

    private int $stock_level;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStockLevel()
    {
        return $this->stock_level;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param mixed $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param mixed $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $stock_level
     * @return Product
     */
    public function setStockLevel($stock_level)
    {
        $this->stock_level = $stock_level;
        return $this;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'description' => $this->description,
            'stock_level' => $this->stock_level
        ];
    }


}