<?php
// Taake errors nazar aayein
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; 

try {
    // localhost:27017 wahi port hai jo aapke Compass mein show ho raha hai
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->sweetcreme_db; 
} catch (Exception $e) {
    die("❌ MongoDB Connection Error: " . $e->getMessage());
}
?>