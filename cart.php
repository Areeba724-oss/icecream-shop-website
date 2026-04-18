<?php
session_start();
// MongoDB connection include karein
include 'config.php'; 

// 1. Database se products fetch karne ka logic (MongoDB style)
$products = [];
try {
    $cursor = $db->products->find();
    foreach ($cursor as $row) {
        $id = (string)$row['_id']; 
        $products[$id] = $row;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// 2. Remove item logic
if(isset($_GET['remove'])){
    $id = $_GET['remove']; // MongoDB String ID
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// 3. Update quantities logic
if(isset($_POST['update_cart'])){
    if(isset($_POST['qty']) && is_array($_POST['qty'])){
        foreach($_POST['qty'] as $id => $qty){
            $qty = (int)$qty;
            if($qty <= 0) unset($_SESSION['cart'][$id]);
            else $_SESSION['cart'][$id] = $qty;
        }
    }
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bag - SweetCreme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #fcfcfc;">

<nav class="navbar" style="position: relative; background: #fff;">
    <div class="nav-container">
        <a href="index.php" class="brand">OD.<span>SweetCreme</span></a>
        <div class="nav-right">
            <a href="index.php" style="color: #666;">Home</a>
            <a href="about.php" style="color: #666;">Our Story</a>
            <div class="cart-icon-wrapper">
                <a href="cart.php" class="cart-btn">
                    <span class="cart-count"><?php echo isset($_SESSION['cart'])?array_sum($_SESSION['cart']):0; ?></span>
                    🛒 Bag
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 50px; max-width: 900px;">
    <h2 style="text-align:center; color: var(--pink); margin-bottom: 30px; font-size: 32px;">Your Shopping Bag</h2>

    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
    <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <form method="POST">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #fce4ec; color: #888; text-transform: uppercase; font-size: 13px;">
                        <th style="padding: 15px 10px;">Product</th>
                        <th style="padding: 15px 10px;">Price</th>
                        <th style="padding: 15px 10px; text-align: center;">Qty</th>
                        <th style="padding: 15px 10px;">Subtotal</th>
                        <th style="padding: 15px 10px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach($_SESSION['cart'] as $id => $qty){
                    // Check karein ke product DB mein exist karta hai
                    if(!isset($products[$id])) continue;
                    
                    $p = $products[$id];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 20px 10px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="<?php echo $p['img']; ?>" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover;">
                                <span style="font-weight: 600; color: #333;"><?php echo $p['name']; ?></span>
                            </div>
                        </td>
                        <td style="color: #666;">Rs <?php echo $p['price']; ?></td>
                        <td style="text-align: center;">
                            <input type='number' name='qty[<?php echo $id; ?>]' value='<?php echo $qty; ?>' min='1' 
                                   style='width: 50px; padding: 5px; border-radius: 5px; border: 1px solid #ddd; text-align: center;'>
                        </td>
                        <td style="font-weight: bold; color: var(--pink);">Rs <?php echo $subtotal; ?></td>
                        <td style="text-align: center;">
                            <a href='cart.php?remove=<?php echo $id; ?>' style="color: #ff4d4d; text-decoration: none; font-size: 14px; font-weight: bold;">✕ Remove</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; background: #fff4f7; padding: 20px; border-radius: 15px;">
                <h3 style="margin: 0; color: #333;">Grand Total:</h3>
                <h3 style="margin: 0; color: var(--pink); font-size: 28px;">Rs <?php echo $total; ?></h3>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; gap: 20px;">
                <button type="submit" name="update_cart" class="add-btn" style="background: #888; width: auto; padding: 12px 30px;">Update Bag</button>
                <a href="checkout.php" style="flex: 1; text-decoration: none;">
                    <button type="button" class="add-btn" style="width: 100%;">Proceed to Checkout</button>
                </a>
            </div>
        </form>
    </div>
    <?php else: ?>
        <div style="text-align:center; padding: 100px 20px;">
            <div style="font-size: 60px; margin-bottom: 20px;">🛒</div>
            <h2 style="color: #ccc;">Your bag is empty!</h2>
            <p style="color: #888; margin-bottom: 30px;">Looks like you haven't added any treats yet.</p>
            <a href="index.php" class="see-menu-btn" style="background: var(--pink); color: white;">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<footer style="text-align: center; padding: 50px; color: #888; font-size: 14px;">
    &copy; 2025 SweetCreme. All Rights Reserved.
</footer>

</body>
</html>