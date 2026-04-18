<?php
session_start();
// Naya config file (MongoDB wala) include karein
include 'config.php'; 

// Fetch products from MongoDB
$products = [];

try {
    // SQL: SELECT * FROM products
    // MongoDB: find() bina kisi filter ke saare documents nikal leta hai
    $cursor = $db->products->find();

    foreach ($cursor as $row) {
        // MongoDB ki ObjectId ko string mein convert karein
        $id = (string)$row['_id']; 
        $products[$id] = $row;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Add to cart logic
if(isset($_POST['add_to_cart'])){
    $id = $_POST['product_id']; // Ye ab MongoDB ki string ID hogi
    if(!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 1;
    else $_SESSION['cart'][$id]++;
    header("Location: products.php"); // prevent resubmission
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products - SweetTreats</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>SweetTreats</header>

<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart (<?php echo isset($_SESSION['cart'])?array_sum($_SESSION['cart']):0; ?>)</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
</nav>

<h2 style="text-align:center; margin:20px 0;">Our Products</h2>

<section class="products">
<?php
if(count($products) > 0){
    foreach($products as $id => $p){
        echo '<div class="product-card">';
        // Ensure karein ke image path aur baqi fields ke naam MongoDB mein sahi hain
        echo '<img src="'.$p['img'].'" alt="'.$p['name'].'">';
        echo '<h3>'.$p['name'].'</h3>';
        echo '<p>Price: Rs '.$p['price'].'</p>';
        echo '<form method="POST">';
        // Yahan $id wahi string hai jo humne ooper foreach mein banayi
        echo '<input type="hidden" name="product_id" value="'.$id.'">';
        echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
        echo '</form></div>';
    }
} else {
    echo "<p style='text-align:center;'>No products available.</p>";
}
?>
</section>

<footer style="text-align:center; margin-top:30px;">&copy; 2025 SweetTreats. All Rights Reserved.</footer>

</body>
</html>