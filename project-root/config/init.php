<?php
require __DIR__ . '/../src/ProductFactory.php'; // Adjust the path as needed
require __DIR__ . '/../src/Book.php'; // Adjust the path as needed
require __DIR__ . '/../src/DVD.php'; // Adjust the path as needed
require __DIR__ . '/../src/Furniture.php'; // Adjust the path as needed

use App\ProductFactory;
use App\Book;
use App\DVD;
use App\Furniture;

// Register product types with the factory
ProductFactory::registerProductType('Book', Book::class);
ProductFactory::registerProductType('DVD', DVD::class);
ProductFactory::registerProductType('Furniture', Furniture::class);