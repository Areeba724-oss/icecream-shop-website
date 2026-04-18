<?php
session_start();
// MongoDB connection include karein
include 'config.php'; 

$total = 0;
$products_in_cart = [];

// 1. Cart se products fetch karne ka logic (MongoDB style)
if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
    foreach($_SESSION['cart'] as $id => $qty){
        try {
            // MongoDB mein id search karne ke liye ObjectId object chahiye hota hai
            $p = $db->products->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            
            if($p){
                $products_in_cart[] = [
                    'name'  => $p['name'], 
                    'qty'   => $qty, 
                    'price' => (int)$p['price']
                ];
                $total += $p['price'] * $qty;
            }
        } catch (Exception $e) {
            // Agar ID galat format mein ho
            continue; 
        }
    }
}

// 2. Order place karne ka logic
if(isset($_POST['place_order']) && count($products_in_cart) > 0){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    try {
        // MongoDB mein direct document insert karein (No need for JSON_ENCODE)
        $insertResult = $db->orders->insertOne([
            'customer_name' => $name,
            'email'         => $email,
            'phone'         => $phone,
            'address'       => $address,
            'items'         => $products_in_cart, // Direct array save ho jayega
            'total_amount'  => $total,
            'status'        => 'pending',
            'order_date'    => new MongoDB\BSON\UTCDateTime()
        ]);

        if($insertResult->getInsertedCount() > 0){
            unset($_SESSION['cart']); 
            $success_msg = "Thank you, $name! Your order has been placed successfully.";
        } else {
            $error_msg = "Error placing order. Please try again.";
        }
    } catch (Exception $e) {
        $error_msg = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SweetCreme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #fcfcfc;">

<nav class="navbar" style="position: relative; background: #fff;">
    <div class="nav-container">
        <a href="index.php" class="brand">OD.<span>SweetCreme</span></a>
        <div class="nav-right">
            <a href="index.php" style="color: #666;">Home</a>
            <a href="cart.php" style="color: #666;">Bag</a>
            <div class="cart-icon-wrapper">
                <a href="cart.php" class="cart-btn">🛒 Bag</a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="max-width: 1000px; margin-top: 40px;">
    <h2 style="text-align:center; color: var(--pink); margin-bottom: 30px;">Complete Your Order</h2>

    <?php if(!isset($success_msg)): ?>
        <?php if(count($products_in_cart) > 0): ?>
            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                
                <div style="flex: 1; min-width: 300px; background: white; padding: 25px; border-radius: 20px; border: 1px solid #eee;">
                    <h3 style="margin-bottom: 20px; border-bottom: 1px solid #fce4ec; padding-bottom: 10px;">Order Summary</h3>
                    <?php foreach($products_in_cart as $item): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span><?php echo $item['name']; ?> (x<?php echo $item['qty']; ?>)</span>
                            <span style="font-weight: bold;">Rs <?php echo $item['price'] * $item['qty']; ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div style="margin-top: 20px; padding-top: 15px; border-top: 2px dashed #eee; display: flex; justify-content: space-between;">
                        <span style="font-weight: bold; font-size: 18px;">Total</span>
                        <span style="font-weight: bold; font-size: 18px; color: var(--pink);">Rs <?php echo $total; ?></span>
                    </div>
                </div>

                <div style="flex: 1.5; min-width: 350px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee;">
                    <form method="POST">
                        <h3 style="margin-bottom: 20px; color: #333;">Delivery Information</h3>
                        <div style="margin-bottom: 15px;">
                            <label style="font-size: 13px; color: #888; font-weight: bold;">Full Name</label>
                            <input type="text" name="name" required style="width:100%; padding:12px; margin-top:5px; border: 1px solid #ddd; border-radius: 8px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="font-size: 13px; color: #888; font-weight: bold;">Email Address</label>
                            <input type="email" name="email" required style="width:100%; padding:12px; margin-top:5px; border: 1px solid #ddd; border-radius: 8px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="font-size: 13px; color: #888; font-weight: bold;">Phone Number</label>
                            <input type="text" name="phone" required style="width:100%; padding:12px; margin-top:5px; border: 1px solid #ddd; border-radius: 8px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 13px; color: #888; font-weight: bold;">Full Delivery Address</label>
                            <textarea name="address" required style="width:100%; padding:12px; margin-top:5px; border: 1px solid #ddd; border-radius: 8px; height: 80px;"></textarea>
                        </div>
                        <button type="submit" name="place_order" class="add-btn" style="width: 100%; font-size: 16px;">Confirm & Place Order</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding: 50px;">
                <p>Your bag is empty. <a href="index.php" style="color: var(--pink);">Go back to menu</a>.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="text-align:center; background: white; padding: 60px 20px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div style="font-size: 80px; margin-bottom: 20px;">🎉</div>
            <h2 style="color: #4CAF50; margin-bottom: 10px;"><?php echo $success_msg; ?></h2>
            <p style="color: #666; margin-bottom: 30px;">Your treats will be delivered shortly!</p>
            <a href="index.php" class="see-menu-btn" style="background: var(--pink); color: white;">Back to Home</a>
        </div>
    <?php endif; ?>

    <?php if(isset($error_msg)): ?>
        <p style='text-align:center; color:red; margin-top: 20px;'><?php echo $error_msg; ?></p>
    <?php endif; ?>
</div>

<footer style="text-align: center; padding: 50px; color: #888; font-size: 14px;">
    &copy; 2025 SweetCreme. All Rights Reserved.
</footer>

</body>
</html>