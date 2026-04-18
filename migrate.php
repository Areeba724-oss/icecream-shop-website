<?php
// Dependencies include karein
require 'vendor/autoload.php';

// 1. MySQL Connection (Purana wala)
$mysql_conn = new mysqli("localhost", "root", "", "sweetcreme_db");

if ($mysql_conn->connect_error) {
    die("MySQL Connection Failed: " . $mysql_conn->connect_error);
}

// 2. MongoDB Connection (Naya wala)
try {
    $mongo_client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongo_client->sweetcreme_db;
    echo "MongoDB Connected!<br>";
} catch (Exception $e) {
    die("MongoDB Connection Failed: " . $e->getMessage());
}

// 3. Products Migrate karein
$result = $mysql_conn->query("SELECT * FROM products");

if ($result->num_rows > 0) {
    $db->products->drop(); // Purana data clear karne ke liye
    while($row = $result->fetch_assoc()) {
        // MySQL ki ID ko chorr kar baqi data insert karein
        $db->products->insertOne([
            'name' => $row['name'],
            'price' => (int)$row['price'],
            'img' => $row['img'],
            'category' => $row['category'] ?? 'general'
        ]);
        echo "Migrated: " . $row['name'] . "<br>";
    }
}

echo "<h3>Migration Complete! Ab aap Compass check karein.</h3>";
?>