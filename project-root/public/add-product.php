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
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h1>Product Add</h1>
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

    <script>
        document.getElementById('type').addEventListener('change', function() {
            var type = this.value;
            document.getElementById('bookFields').style.display = (type === 'Book') ? 'block' : 'none';
            document.getElementById('dvdFields').style.display = (type === 'DVD') ? 'block' : 'none';
            document.getElementById('furnitureFields').style.display = (type === 'Furniture') ? 'block' : 'none';
        });
    </script>
</body>
</html>
