<?php
namespace App;

class Furniture extends Product {
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->setDimensions($height, $width, $length);
    }

    public function save($database) {
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
        " 
        <div>{$this->sku}</div>
        <div>{$this->name}</div>
        <div>{$this->price} $</div>
        <div>Dimensions: {$this->height}x{$this->width}x{$this->length}</div>
        ";
    }

    public function setDimensions($height, $width, $length) {
        if (!is_numeric($height) || !is_numeric($width) || !is_numeric($length)) {
            throw new \InvalidArgumentException("All dimensions must be numeric values.");
        }
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }
    
    public function getDimensions() {
        return [
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length
        ];
    }
}
