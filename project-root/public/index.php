<?php
require '../vendor/autoload.php';
use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('../google-service-account.json')
    ->withDatabaseUri('https://test-task-php-default-rtdb.europe-west1.firebasedatabase.app/');

$database = $factory->createDatabase();
$reference = $database->getReference('products');
$products = $reference->getValue();
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
            <ul>
                <?php foreach ($products as $product): ?>
                    <li><?php echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']) . ' $'; ?></li>
                <?php endforeach; ?>
            </ul>  
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
