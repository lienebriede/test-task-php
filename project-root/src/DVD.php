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
        return "
        <div>{$this->sku}</div>
        <div>{$this->name}</div>
        <div>{$this->price} $</div>
        <div>Size: {$this->size} MB</div>
        ";
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }
}
