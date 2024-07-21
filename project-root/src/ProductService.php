<?php
namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Exception;

class ProductService
{
    private $database;
    private $logFile;

    public function __construct(string $firebaseCredentialsPath, string $firebaseDatabaseUrl, string $logFile)
    {
        $factory = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->withDatabaseUri($firebaseDatabaseUrl);

        $this->database = $factory->createDatabase();
        $this->logFile = $logFile;
    }

    public function checkSku(string $sku): bool
    {
        $this->logMessage("Checking SKU: $sku");

        $reference = $this->database->getReference('products')->orderByChild('sku')->equalTo($sku);
        $snapshot = $reference->getSnapshot();

        $skuExists = $snapshot->hasChildren();
        $this->logMessage("SKU Exists: " . ($skuExists ? 'true' : 'false'));

        return $skuExists;
    }

    public function deleteProducts(array $deleteIds): void
    {
        foreach ($deleteIds as $sku) {
            // Validate SKU format
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $sku)) {
                throw new Exception("Invalid SKU format: $sku");
            }

            // Delete the product with $sku
            $reference = $this->database->getReference('products')->orderByChild('sku')->equalTo($sku);
            $snapshot = $reference->getSnapshot();

            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $key => $value) {
                    $this->database->getReference('products')->getChild($key)->remove();
                }
                $this->logMessage("Product with SKU $sku deleted successfully");
            } else {
                $this->logMessage("Product with SKU $sku not found for deletion");
            }
        }
    }

    private function logMessage(string $message): void
    {
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }
}