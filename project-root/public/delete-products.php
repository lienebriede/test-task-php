<?php
require '../vendor/autoload.php';
use Kreait\Firebase\Factory;

// Define logging function
function log_message($message) {
    $log_file = '../logs/debug.log';
    if (!file_exists($log_file)) {
        file_put_contents($log_file, '');
    }
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

try {
    // Initialize Firebase
    $factory = (new Factory)
        ->withServiceAccount('../google-service-account.json')
        ->withDatabaseUri('https://test-task-php-default-rtdb.europe-west1.firebasedatabase.app/');

    $database = $factory->createDatabase();

    // Handle mass delete operation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);

        // Ensure required parameters are set
        if (!isset($data['mass_delete']) || !isset($data['delete_ids'])) {
            throw new Exception("Missing parameters: mass_delete or delete_ids");
        }

        $massDelete = filter_var($data['mass_delete'], FILTER_VALIDATE_BOOLEAN);
        $deleteIds = $data['delete_ids'];

        // Validate delete_ids as an array
        if (!is_array($deleteIds)) {
            throw new Exception("delete_ids must be an array");
        }

        foreach ($deleteIds as $sku) {
            // Validate SKU format to prevent injection or unexpected values
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $sku)) {
                throw new Exception("Invalid SKU format: $sku");
            }

            // Delete the product with $sku
            $reference = $database->getReference('products')->orderByChild('sku')->equalTo($sku);
            $snapshot = $reference->getSnapshot();

            // Check if there are any children (matched nodes)
            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $key => $value) {
                    // Remove each child node individually
                    $database->getReference('products')->getChild($key)->remove();
                }
                log_message("Product with SKU $sku deleted successfully");
            } else {
                log_message("Product with SKU $sku not found for deletion");
            }
        }

        // Respond with success
        http_response_code(200);
        exit;
    } else {
        throw new Exception("Invalid request method or missing parameters");
    }
} catch (Exception $e) {
    // Log any exceptions that occur during Firebase operations or parameter validation
    log_message("Error: " . $e->getMessage());
    // Respond with internal server error
    http_response_code(500);
    exit;
}
?>