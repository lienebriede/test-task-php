<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/init.php';

use App\ProductFactory;

$reference = $database->getReference('products');
$productsSnapshot = $reference->getSnapshot();

$products = [];
foreach ($productsSnapshot->getValue() as $product) {
    $productData = [
        'type' => $product['type'],
        'sku' => $product['sku'],
        'name' => $product['name'],
        'price' => isset($product['price']) ? (float)$product['price'] : 0,
        'weight' => isset($product['weight']) ? (float)$product['weight'] : 0,
        'size' => isset($product['size']) ? (float)$product['size'] : 0,
        'dimensions' => isset($product['dimensions']) ? $product['dimensions'] : []
    ];

    $products[] = ProductFactory::createProduct($productData);
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
                    <h1>Product List</h1>
                </div>
                <div class="col-auto">
                    <a href="add-product.php" id="submitButton" role="submit" class="btn btn-primary">Add</a>
                    <button id="massDeleteButton" type="submit" class="btn btn-danger">Mass delete</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="container py-3">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-5">
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100">
                            <input type="checkbox" name="delete_ids[]" value="<?php echo $product->getSku(); ?>" class="text-left ms-2 mt-2 form-check-input delete-checkbox">
                            <div class="card-body text-center mb-3">
                                <?php echo $product->display(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
