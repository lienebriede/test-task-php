<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Service\ProductService;
use App\Service\ResponseHandler;

class MassDeleteHandler {
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
                if (!isset($data['mass_delete']) || !isset($data['delete_ids'])) {
                    throw new Exception("Missing parameters: mass_delete or delete_ids");
                }

                $massDelete = filter_var($data['mass_delete'], FILTER_VALIDATE_BOOLEAN);
                $deleteIds = $data['delete_ids'];

                if (!is_array($deleteIds)) {
                    throw new Exception("delete_ids must be an array");
                }

                $this->productService->deleteProducts($deleteIds);
                $this->responseHandler->sendResponse(200);
            } else {
                throw new Exception("Invalid request method or missing parameters");
            }
        } catch (Exception $e) {
            $this->productService->logMessage("Error: " . $e->getMessage());
            $this->responseHandler->sendResponse(500);
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

$handler = new MassDeleteHandler($productService, $responseHandler);
$handler->handleRequest();
?>