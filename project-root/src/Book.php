<?php

namespace App;

class Book extends Product {
    private $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }

    public function save($database) {
       $reference = $database->getReference('products')->push([
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'weight' => $this->weight,
            'type' => 'Book'
        ]);

        return $reference->getKey() !== null;
    }

    public function display() {
        return "
        <div>{$this->sku}</div>
        <div>{$this->name}</div>
        <div>{$this->price} $</div>
        <div>Weight: {$this->weight} KG</div>
        ";
    }

    public function getWeight() {
        return $this->weight;
    }
    
    public function setWeight($weight) {
        if (!is_numeric($weight)) {
            throw new \InvalidArgumentException("Weight must be a numeric value.");
        }
        $this->weight = $weight;
    }
}