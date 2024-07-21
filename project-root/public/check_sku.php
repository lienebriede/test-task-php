<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Service\ProductService;
use App\Service\ResponseHandler;

class SKUValidationHandler {
    private $productService;
    private $responseHandler;

    public function __construct(ProductService $productService, ResponseHandler $responseHandler) {
        $this->productService = $productService;
        $this->responseHandler = $responseHandler;
    }

    public function handleRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->isJsonRequest()) {
                $data = json_decode(file_get_contents('php://input'), true);
                if (isset($data['sku'])) {
                    $skuExists = $this->productService->checkSku($data['sku']);
                    $this->responseHandler->sendJsonResponse(['unique' => !$skuExists]);
                } else {
                    throw new Exception("SKU parameter missing");
                }
            } else {
                throw new Exception("Invalid request");
            }
        } catch (Exception $e) {
            $this->productService->logMessage("Error: " . $e->getMessage());
            $this->responseHandler->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    private function isJsonRequest() {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }
}

$firebaseCredentialsPath = __DIR__ . '/../google-service-account.json';
$firebaseDatabaseUrl = getenv('FIREBASE_DATABASE_URL');
$logFile = __DIR__ . '/../logs/debug.log';

$productService = new ProductService($firebaseCredentialsPath, $firebaseDatabaseUrl, $logFile);
$responseHandler = new ResponseHandler();

$handler = new SKUValidationHandler($productService, $responseHandler);
$handler->handleRequest();
?>