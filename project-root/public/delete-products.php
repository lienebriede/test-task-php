<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/ProductService.php';

use App\Service\ProductService;

$firebaseCredentialsPath = __DIR__ . '/../google-service-account.json';
$firebaseDatabaseUrl = getenv('FIREBASE_DATABASE_URL');
$logFile = __DIR__ . '/../logs/debug.log';

$productService = new ProductService($firebaseCredentialsPath, $firebaseDatabaseUrl, $logFile);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['mass_delete']) || !isset($data['delete_ids'])) {
            throw new Exception("Missing parameters: mass_delete or delete_ids");
        }

        $massDelete = filter_var($data['mass_delete'], FILTER_VALIDATE_BOOLEAN);
        $deleteIds = $data['delete_ids'];

        if (!is_array($deleteIds)) {
            throw new Exception("delete_ids must be an array");
        }

        $productService->deleteProducts($deleteIds);

        http_response_code(200);
        exit;
    } else {
        throw new Exception("Invalid request method or missing parameters");
    }
} catch (Exception $e) {
    $productService->logMessage("Error: " . $e->getMessage());
    http_response_code(500);
    exit;
}
?>