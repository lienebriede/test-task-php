<?php
namespace App;

class Furniture extends Product {
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height = null, $width = null, $length = null) {
        parent::__construct($sku, $name, $price);
        $this->dimensions = [];
    }

    public function save($database) {
        echo "Saving Furniture to Firebase...\n";
        $reference = $database->getReference('products')->push([
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'dimensions' => [
                'height' => $this->height,
                'width' => $this->width,
                'length' => $this->length
            ],
            'type' => 'Furniture'
        ]);

        return $reference->getKey() !== null;
    }
    
    public function display() {
        return 
        "SKU: {$this->sku}, 
        Name: {$this->name}, 
        Price: {$this->price} $, 
        Dimensions: Height{$this->height} x Width{$this->width} x Length{$this->length} cm";
    }

    public function setDimensions($height, $width, $length) {
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }
    
    public function getDimensions() {
        return $this->dimensions;
    }
}
