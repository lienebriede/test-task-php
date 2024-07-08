<?php
// Include Composer's autoloader
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Path to your serviceAccountKey.json file
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/firebase/serviceAccountKey.json');

// Initialize Firebase
$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->create();

// Get the Firebase Realtime Database instance
$database = $firebase->getDatabase();

// Now you can interact with the Firebase Realtime Database
// For example, pushing data to the database
$reference = $database->getReference('test')->push([
    'name' => 'John Doe',
    'age' => 30,
    'city' => 'New York'
]);

echo "Data pushed to Firebase with key: " . $reference->getKey() . PHP_EOL;