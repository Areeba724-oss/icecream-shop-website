<?php
session_start();
include 'config.php';

// Optional: Agar aapne database mein 'settings' ya 'about' naam ki collection banai hai
// toh wahan se text utha sakte hain. Warna static text bhi theek hai.
try {
    $content = $db->about_page->findOne(); // Database se about info nikalna
} catch (Exception $e) {
    $content = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - SweetTreats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="brand">OD.<span>SweetTreats</span></a>
        <div class="nav-right">
            <a href="index.php">Home</a>
            <a href="about.php" style="color: var(--pink) !important; border-bottom: 2px solid var(--pink);">Our Story</a>
            <div class="cart-icon-wrapper">
                <a href="cart.php" class="cart-btn">
                    <span class="cart-count"><?php echo isset($_SESSION['cart'])?array_sum($_SESSION['cart']):0; ?></span>
                    🛒 Bag
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="hero-wrap">
    <div class="hero-bg" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.3)), url('images/aboutus.jpg'); height: 350px;">
        <div class="hero-content">
            <h1>Our Story</h1>
            <p>Crafting sweetness since 2018 🍦</p>
        </div>
    </div>
</div>

<section class="container">
    <div class="about-container" style="display: flex; gap: 40px; align-items: center; margin-top: 40px; background: #fff; padding: 40px; border-radius: 20px; border: 1px solid #eee;">
        <div class="about-img" style="flex: 1;">
            <img src="images/aboutus.jpg" alt="About SweetCreme" style="width: 100%; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
        </div>
        <div class="about-text" style="flex: 1;">
            <h2 style="color: var(--pink); font-size: 32px; margin-bottom: 20px;">
                <?php echo $content['title'] ?? 'Welcome to SweetTreats'; ?>
            </h2>
            <p style="line-height: 1.8; color: #666; font-size: 16px; margin-bottom: 15px;">
                <?php echo $content['description'] ?? 'SweetCreme has been serving delicious desserts and soft-serve since 2018. Our mission is to bring happiness to every bite with fresh ingredients and love.'; ?>
            </p>
            <p style="line-height: 1.8; color: #666; font-size: 16px;">
                We take pride in our quality and customer satisfaction. Visit us or order online to taste the best desserts in town!
            </p>
            <a href="index.php#products-section" class="see-menu-btn" style="margin-top: 25px; background: var(--pink); color: white;">Order Now</a>
        </div>
    </div>
</section>

<footer style="text-align: center; padding: 40px; color: #888; font-size: 14px;">
    &copy; 2025 SweetTreats. All Rights Reserved.
</footer>

</body>
</html>