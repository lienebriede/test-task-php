<?php

namespace App;

class Book extends Product {
    private $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }

    public function save($database) {
        echo "Saving Book to Firebase...\n";
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
        return "SKU: {$this->sku}, Name: {$this->name}, Price: {$this->price} $, Weight: {$this->weight} Kg";
    }

    public function getWeight() {
        return $this->weight;
    }
    
    public function setWeight($weight) {
        $this->weight = $weight;
    }
}