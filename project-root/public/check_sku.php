<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/ProductService.php';

use App\Service\ProductService;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration
$firebaseCredentialsPath = __DIR__ . '/../google-service-account.json';
$firebaseDatabaseUrl = getenv('FIREBASE_DATABASE_URL');
$logFile = __DIR__ . '/../logs/debug.log';

// Initialize ProductService
$productService = new ProductService($firebaseCredentialsPath, $firebaseDatabaseUrl, $logFile);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['sku'])) {
            $sku = $data['sku'];

            $skuExists = $productService->checkSku($sku);

            header('Content-Type: application/json');
            echo json_encode(['unique' => !$skuExists]);
            exit;
        }
    }

    // Invalid request handling
    $productService->logMessage("Invalid request");
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request']);
} catch (Exception $e) {
    $productService->logMessage("Error: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Internal server error']);
}
?>