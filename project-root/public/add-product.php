<?php
require '../vendor/autoload.php';
use Kreait\Firebase\Factory;
use App\Book;
use App\DVD;
use App\Furniture;

$factory = (new Factory)
    ->withServiceAccount('../google-service-account.json')
    ->withDatabaseUri('https://test-task-php-default-rtdb.europe-west1.firebasedatabase.app/');

$database = $factory->createDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if ($type === 'Book') {
        $weight = $_POST['weight'];
        $product = new Book($sku, $name, $price, $weight);
    } elseif ($type === 'DVD') {
        $size = $_POST['size'];
        $product = new DVD($sku, $name, $price, $size);
    } elseif ($type === 'Furniture') {
        $height = $_POST['height'];
        $width = $_POST['width'];
        $length = $_POST['length'];
        $product = new Furniture($sku, $name, $price, $height, $width, $length);
    }

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
    <form method="POST" action="add-product.php">
        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" required><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br>

        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="Book">Book</option>
            <option value="DVD">DVD</option>
            <option value="Furniture">Furniture</option>
        </select><br>

        <div id="bookFields" style="display:none;">
            <label for="weight">Weight (kg):</label>
            <input type="text" id="weight" name="weight"><br>
        </div>

        <div id="dvdFields" style="display:none;">
            <label for="size">Size (MB):</label>
            <input type="text" id="size" name="size"><br>
        </div>

        <div id="furnitureFields" style="display:none;">
            <label for="height">Height (cm):</label>
            <input type="text" id="height" name="height"><br>

            <label for="width">Width (cm):</label>
            <input type="text" id="width" name="width"><br>

            <label for="length">Length (cm):</label>
            <input type="text" id="length" name="length"><br>
        </div>

        <button type="submit">Add Product</button>
    </form>
    <hr>
    <footer class="footer">
        <p class="text-center mt-4">Scandiweb Test assignment</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
