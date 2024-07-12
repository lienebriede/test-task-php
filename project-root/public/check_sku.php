<?php
require __DIR__ . '/../vendor/autoload.php';
use Kreait\Firebase\Factory;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Logging function
function log_message($message) {
    $log_file = '../logs/debug.log';
    if (!file_exists($log_file)) {
        file_put_contents($log_file, '');
    }
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

log_message("check_sku.php called");

try {
    // Initialize Firebase database
    $firebaseCredentialsPath = __DIR__ . '/../google-service-account.json'; // Adjust the path as necessary
    $firebaseDatabaseUrl = getenv('FIREBASE_DATABASE_URL'); // Adjust environment variable name if needed

    $factory = (new Factory)
        ->withServiceAccount($firebaseCredentialsPath)
        ->withDatabaseUri($firebaseDatabaseUrl);

    $database = $factory->createDatabase();

    log_message("Database initialized");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['sku'])) {
            $sku = $data['sku'];
            log_message("SKU: $sku");

            $reference = $database->getReference('products')->orderByChild('sku')->equalTo($sku);
            $snapshot = $reference->getSnapshot();

            $skuExists = $snapshot->hasChildren();
            log_message("SKU Exists: " . ($skuExists ? 'true' : 'false'));

            header('Content-Type: application/json');
            echo json_encode(['unique' => !$skuExists]);
            exit;
        }
    }

    log_message("Invalid request");

    header('Content-Type: application/json');
} catch (Exception $e) {
    log_message("Error: " . $e->getMessage());
    header('Content-Type: application/json');
}
?>