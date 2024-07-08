<?php
namespace App;

class DVD extends Product {
    private $size; 

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    public function save($database) {
        echo "Saving DVD to Firebase...\n";
        $reference = $database->getReference('products')->push([
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'size' => $this->size,
            'type' => 'DVD'
        ]);

        return $reference->getKey() !== null;

    }

    public function display() {
        return "SKU: {$this->sku}, Name: {$this->name}, Price: {$this->price} $, Size: {$this->size} MB";
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }
}
