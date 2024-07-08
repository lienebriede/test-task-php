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
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?php echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']) . ' $'; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="add-product.php">Add Product</a>
</body>
</html>
