<?php
session_start();
// 1. Connection include karein taake product data DB se aaye
include 'config.php'; 

// Pehle aapne static array ($products) banaya tha, 
// ab hum isay database se fetch karenge jahan zaroorat hogi.

// Add product to cart
if(isset($_POST['add_to_cart'])){
    $id = $_POST['product_id']; // Ye ab MongoDB ki String ID hogi
    if(!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 1;
    else $_SESSION['cart'][$id]++;
    header("Location: products.php");
    exit;
}

// Remove item from cart
if(isset($_GET['remove'])){
    $id = $_GET['remove']; // String ID
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Update cart quantities
if(isset($_POST['update_cart'])){
    if(isset($_POST['qty']) && is_array($_POST['qty'])){
        foreach($_POST['qty'] as $id => $qty){
            $qty = (int)$qty; // Number mein convert karein
            if($qty <= 0) unset($_SESSION['cart'][$id]);
            else $_SESSION['cart'][$id] = $qty;
        }
    }
    header("Location: cart.php");
    exit;
}
?>