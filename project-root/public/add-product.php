<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/init.php'; // Include initialization

use App\ProductFactory;
use App\Service\ResponseHandler;
use App\Book;
use App\DVD;
use App\Furniture;

// Create instance of ResponseHandler
$responseHandler = new ResponseHandler();

// Check for unique SKU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['sku'])) {
        $sku = $data['sku'];
        $reference = $database->getReference('products')->orderByChild('sku')->equalTo($sku);
        $snapshot = $reference->getSnapshot();

        $skuExists = $snapshot->hasChildren();

        $responseHandler->sendJsonResponse(['unique' => !$skuExists]);
    }
    exit; // Ensure the script stops after sending a response
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (!is_numeric($price)) {
        $responseHandler->sendJsonResponse(['error' => 'Price must be numeric.'], 400);
    }
    $price = (float)$price;

    $reference = $database->getReference('products')->orderByChild('sku')->equalTo($sku);
    $snapshot = $reference->getSnapshot();

    if ($snapshot->hasChildren()) {
        $responseHandler->sendJsonResponse(['error' => 'SKU already exists.'], 400);
    }

    $productData = [
        'type' => $type,
        'sku' => $sku,
        'name' => $name,
        'price' => $price,
        'weight' => $_POST['weight'] ?? null,
        'size' => $_POST['size'] ?? null,
        'dimensions' => [
            'height' => $_POST['height'] ?? null,
            'width' => $_POST['width'] ?? null,
            'length' => $_POST['length'] ?? null,
        ]
    ];

    // Create object using the factory
    $product = ProductFactory::createProduct($productData);

    // Save object to database and redirect to display
    $product->save($database);
    header('Location: index.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junior Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column h-100 container-lg">
    <main class="container content">
        <div class="mt-5">
            <div class="row align-items-center">
                <div class="col">
                    <h1>Product Add</h1>
                </div>
                <div class="col-auto">
                    <button id="submitButton" type="submit" class="btn btn-primary">Save</button>
                    <button id="cancelButton" type="button" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
        <hr>
        <form method="POST" action="add-product.php" id="product_form" class="pt-5">
            <div class="form-group row mb-3">
                <label for="sku" class="col-2 col-form-label">SKU</label>
                <div class="col-6">
                    <input type="text" class="form-control" id="sku" name="sku" required>
                    <small id="skuError" class="text-danger d-none">SKU already exists.</small>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="name" class="col-2 col-form-label">Name</label>
                <div class="col-6">
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="price" class="col-2 col-form-label">Price($)</label>
                <div class="col-6">
                    <input type="text" class="form-control" id="price" name="price" required>
                    <small id="priceError" class="text-danger d-none">Price must be a numeric value.</small>
                </div>
            </div>

            <div class="form-group row mt-5 mb-3">
                <label for="productType" class="col-3 col-form-label">Type Switcher</label>
                <div class="col-5">
                    <select id="productType" class="form-control form-select" name="type" required>
                        <option value="">Choose Type</option>    
                        <option value="DVD">DVD</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Book">Book</option>
                    </select>
                </div>
            </div>

            <div id="DVD" class="d-none pt-3">
                <p><strong>Please provide size:</strong></p>
                <div class="form-group row">
                    <label for="size" class="col-3 col-form-label">Size (MB)</label>
                    <div class="col-3">
                        <input type="text" id="size" name="size" class="form-control">
                        <small id="sizeError" class="text-danger d-none">Size must be a numeric value.</small>
                    </div>
                </div>
            </div>

            <div id="Furniture" class="d-none pt-3">
                <p><strong>Please provide dimensions:</strong></p>
                <div class="form-group row mb-3">
                    <label for="height" class="col-3 col-form-label">Height (CM)</label>
                    <div class="col-3">
                        <input type="text" id="height" name="height" class="form-control">
                        <small id="heightError" class="text-danger d-none">Height must be a numeric value.</small>
                    </div>
                </div>  
                <div class="form-group row mb-3">  
                    <label for="width" class="col-3 col-form-label">Width (CM)</label>
                    <div class="col-3">
                        <input type="text" id="width" name="width" class="form-control">
                        <small id="widthError" class="text-danger d-none">Width must be a numeric value.</small>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="length" class="col-3 col-form-label">Length (CM)</label>
                    <div class="col-3">
                        <input type="text" id="length" name="length" class="form-control">
                        <small id="lengthError" class="text-danger d-none">Length must be a numeric value.</small>
                    </div>
                </div>
            </div>
            
            <div id="Book" class="d-none pt-3">
                <p><strong>Please provide weight:</strong></p>
                <div class="form-group row">
                    <label for="weight" class="col-3 col-form-label">Weight (KG)</label>
                    <div class="col-3">
                        <input type="text" id="weight" name="weight" class="form-control">
                        <small id="weightError" class="text-danger d-none">Weight must be a numeric value.</small>
                    </div>
                </div>
            </div>
        </form>
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="customAlertModal" tabindex="-1" aria-labelledby="customAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customAlertModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please submit required data.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
    </main>
    <footer class="footer container">
        <hr>
        <p class="text-center my-5">Scandiweb Test assignment</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
