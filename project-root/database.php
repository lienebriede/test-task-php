<?php

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use App\DVD;
use App\Book;
use App\Furniture;

$factory = (new Factory)
    ->withServiceAccount(__DIR__.'/google-service-account.json')
    ->withDatabaseUri('https://test-task-php-default-rtdb.europe-west1.firebasedatabase.app/');

$database = $factory->createDatabase();

function addProduct($product) {
    global $database;

    $product->save($database);

    echo $product->display() . "\n";
}

// Add DVDs
$dvds = [
    new DVD('DIS001', 'Aladdin', 19.99, '1200'),
    new DVD('DIS002', 'Moana', 17.99, '1000'),
    new DVD('DIS003', 'Frozen', 21.99, '1300'),
    new DVD('DIS004', 'Beauty and the Beast', 24.99, '1500')
];

foreach ($dvds as $dvd) {
    addProduct($dvd);
}

// Add Books
$books = [
    new Book('HP001', 'Harry Potter and the Philosopher\'s Stone', 14.99, 1.2),
    new Book('HP002', 'Harry Potter and the Chamber of Secrets', 15.99, 1.3),
    new Book('HP003', 'Harry Potter and the Prisoner of Azkaban', 16.99, 1.4),
    new Book('HP004', 'Harry Potter and the Goblet of Fire', 17.99, 1.5),
    new Book('HP005', 'Harry Potter and the Order of the Phoenix', 18.99, 1.6),
    new Book('HP006', 'Harry Potter and the Half-Blood Prince', 19.99, 1.7),
    new Book('HP007', 'Harry Potter and the Deathly Hallows', 20.99, 1.8)
];

foreach ($books as $book) {
    addProduct($book);
}

// Add Furniture
$furniture = [
    new Furniture('FURN001', 'Small Desk', 99.99),
    new Furniture('FURN002', 'Large Desk', 149.99),
    new Furniture('FURN003', 'Small Bookshelf', 79.99),
    new Furniture('FURN004', 'Large Bookshelf', 129.99)
];

// Set dimensions for each furniture item
$furniture[0]->setDimensions(70, 100, 50);
$furniture[1]->setDimensions(90, 150, 70);
$furniture[2]->setDimensions(120, 60, 30);
$furniture[3]->setDimensions(180, 80, 40);

foreach ($furniture as $item) {
    addProduct($item);
}
