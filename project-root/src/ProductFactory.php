<?php
namespace App;

use Exception;

class ProductFactory
{
    private static $productCreators = [];

    public static function registerProductType($type, $className)
    {
        self::$productCreators[$type] = $className;
    }

    public static function createProduct(array $data)
    {
        $type = $data['type'] ?? null;
        $className = self::$productCreators[$type] ?? null;

        if (!$className) {
            throw new Exception('Invalid product type');
        }

        return self::instantiateProduct($className, $data);
    }

    private static function instantiateProduct($className, array $data)
    {
        $params = [
            'sku' => $data['sku'],
            'name' => $data['name'],
            'price' => (float)($data['price'] ?? 0)
        ];

        switch ($className) {
            case Book::class:
                $params['weight'] = (float)($data['weight'] ?? 0);
                break;
            case DVD::class:
                $params['size'] = (float)($data['size'] ?? 0);
                break;
            case Furniture::class:
                $params['height'] = (float)($data['dimensions']['height'] ?? 0);
                $params['width'] = (float)($data['dimensions']['width'] ?? 0);
                $params['length'] = (float)($data['dimensions']['length'] ?? 0);
                break;
        }

        return new $className(...array_values($params));
    }
}