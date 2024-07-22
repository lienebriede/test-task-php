<?php
require __DIR__ . '/../src/ProductFactory.php';
require __DIR__ . '/../src/Book.php';
require __DIR__ . '/../src/DVD.php';
require __DIR__ . '/../src/Furniture.php';

use App\ProductFactory;
use App\Book;
use App\DVD;
use App\Furniture;

ProductFactory::registerProductType('Book', Book::class);
ProductFactory::registerProductType('DVD', DVD::class);
ProductFactory::registerProductType('Furniture', Furniture::class);