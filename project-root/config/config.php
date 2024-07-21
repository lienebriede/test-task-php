<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

$environment = getenv('ENVIRONMENT') ?: 'local';

if ($environment === 'heroku') {
    $firebaseCredentialsPath = __DIR__ . '/../google-service-account.json';
    
    $firebaseCredentialsContent = getenv('GOOGLE_CREDENTIALS_JSON');
    if ($firebaseCredentialsContent) {
        file_put_contents($firebaseCredentialsPath, $firebaseCredentialsContent);
    } else {
        error_log('GOOGLE_CREDENTIALS_JSON environment variable is not set or empty.');
        die('GOOGLE_CREDENTIALS_JSON environment variable is not set or empty.');
    }
} else {
    $firebaseCredentialsPath = __DIR__ . '/../google-service-account.json';
}

$firebaseDatabaseUrl = getenv('FIREBASE_DATABASE_URL');
if (!$firebaseDatabaseUrl) {
    error_log('FIREBASE_DATABASE_URL environment variable is not set or empty.');
    die('FIREBASE_DATABASE_URL environment variable is not set or empty.');
}

$factory = (new Factory)
    ->withServiceAccount($firebaseCredentialsPath)
    ->withDatabaseUri($firebaseDatabaseUrl);

$database = $factory->createDatabase();