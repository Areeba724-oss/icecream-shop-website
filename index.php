<?php
session_start();
include 'config.php'; 

$cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';

// Cart Logic
if(isset($_POST['add_to_cart'])){
    $id = $_POST['product_id'];
    if(!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 1;
    else $_SESSION['cart'][$id]++;
    header("Location: index.php?cat=$cat#products-section");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetCreme | OD Edition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --pink: #ff6b81;
            --soft-pink: #ff8e9e;
            --cream: #fffafb;
            --dark: #2d3436;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        
        html { scroll-behavior: smooth; }
        body { background-color: var(--cream); color: var(--dark); }

        /* Navbar */
        .navbar {
            background: #fff;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .brand { font-family: 'Playfair Display', serif; font-size: 24px; text-decoration: none; color: var(--dark); }
        .brand span { color: var(--pink); }
        .nav-right a { text-decoration: none; color: #666; margin-left: 25px; font-weight: 500; transition: 0.3s; }
        .nav-right a:hover { color: var(--pink); }
        .cart-btn { background: var(--pink); color: white !important; padding: 8px 18px; border-radius: 50px; }

        /* Category Nav */
        .category-nav { background: #fff; padding: 20px 0; border-bottom: 1px solid #f1f1f1; }
        .cat-links { text-align: center; }
        .cat-links a {
            text-decoration: none;
            color: #888;
            padding: 10px 25px;
            margin: 0 5px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
            border: 1px solid transparent;
        }
        .cat-links a.active, .cat-links a:hover {
            background: var(--light-pink);
            color: var(--pink);
            border-color: var(--pink);
        }

        /* Hero Section */
        .hero-wrap {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=1300&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .hero-content h1 { font-family: 'Playfair Display', serif; font-size: 50px; margin-bottom: 10px; }
        .see-menu-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 35px;
            background: var(--pink);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }
        .see-menu-btn:hover { background: var(--soft-pink); transform: translateY(-3px); }

        /* Products Grid */
        #products-section { padding: 80px 20px; max-width: 1200px; margin: 0 auto; }
        .page-title { text-align: center; margin-bottom: 50px; }
        .page-title h2 { font-family: 'Playfair Display', serif; font-size: 36px; position: relative; display: inline-block; }
        .page-title h2::after {
            content: '';
            width: 60px;
            height: 3px;
            background: var(--pink);
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 20px;
            text-align: center;
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(255, 107, 129, 0.15); }
        .product-card img { width: 100%; height: 250px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
        .product-card h3 { font-size: 18px; margin-bottom: 10px; color: var(--dark); }
        .price { color: var(--pink); font-weight: 700; font-size: 20px; margin-bottom: 15px; }
        
        .add-btn {
            width: 100%;
            padding: 12px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .add-btn:hover { background: var(--pink); }

        @media (max-width: 768px) {
            .hero-content h1 { font-size: 32px; }
            .cat-links a { padding: 8px 15px; font-size: 12px; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="brand">OD.<span>SweetCreme</span></a>
        <div class="nav-right">
            <a href="about.php">Our Story</a>
            <a href="cart.php" class="cart-btn">
                <span class="cart-count"><?php echo isset($_SESSION['cart'])?array_sum($_SESSION['cart']):0; ?></span>
                🛒 Bag
            </a>
        </div>
    </div>
</nav>

<div class="category-nav" id="menu">
    <div class="cat-links">
        <a href="index.php?cat=all#products-section" class="<?php echo $cat=='all'?'active':''; ?>">WINTER DEALS</a>
        <a href="index.php?cat=donuts#products-section" class="<?php echo $cat=='donuts'?'active':''; ?>">DONUTS</a>
        <a href="index.php?cat=cupcakes#products-section" class="<?php echo $cat=='cupcakes'?'active':''; ?>">CUPCAKES</a>
        <a href="index.php?cat=icecream#products-section" class="<?php echo $cat=='icecream'?'active':''; ?>">ICE CREAM</a>
    </div>
</div>

<div class="hero-wrap">
    <div class="hero-content">
        <h1>Handcrafted Happiness</h1>
        <p>Because every day deserves a little treat 🍰</p>
        <a href="#menu" class="see-menu-btn">View Menu</a>
    </div>
</div>

<div id="products-section">
    <header class="page-title">
        <h2><?php echo ($cat == 'all') ? "Winter Specials 2026" : strtoupper($cat); ?></h2>
    </header>

    <section class="container">
        <div class="products-grid">
            <?php 
            $products = [];
            try {
                if($cat == 'all'){
                    $cursor = $db->products->find(['name' => new MongoDB\BSON\Regex('Deal|Combo', 'i')]);
                } else {
                    $cursor = $db->products->find(['category' => $cat]);
                }
                foreach ($cursor as $doc) {
                    $products[(string)$doc['_id']] = [
                        'name' => $doc['name'],
                        'price' => $doc['price'],
                        'img' => $doc['img']
                    ];
                }
            } catch (Exception $e) {
                echo "<p style='text-align:center; color:red;'>Database Error. Please check MongoDB.</p>";
            }

            if(count($products) > 0): 
                foreach($products as $id => $p): ?>
                <div class="product-card">
                    <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['name']; ?>">
                    <h3><?php echo $p['name']; ?></h3>
                    <p class="price">Rs <?php echo $p['price']; ?></p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <button type="submit" name="add_to_cart" class="add-btn">Add to Bag</button>
                    </form>
                </div>
                <?php endforeach; 
            else: ?>
                <p style="grid-column: span 3; text-align:center; padding:50px; color:#999;">No treats found here!</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<footer style="text-align: center; padding: 50px; color: #bbb; font-size: 14px;">
    &copy; 2026 OD.SweetCreme Edition
</footer>

</body>
</html>