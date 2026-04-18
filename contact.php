<?php
session_start();
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SweetCreme</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* Agar CSS link kaam na kare toh ye fallback styling hai */
        body {
            background-color: #fff;
            margin: 0;
        }

        /* Form ko center aur sundar banane ke liye */
        .contact-container {
            max-width: 550px;
            margin: 50px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid var(--light-pink);
        }

        .contact-container h2 {
            text-align: center;
            color: #8b5e3c;
            margin-bottom: 30px;
            font-size: 28px;
            text-transform: uppercase;
        }

        /* Sabse important part: Is se inputs niche aayenge */
        .input-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .input-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .input-group input, 
        .input-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.3s ease;
        }

        /* Focus effect aapke pink theme ke mutabiq */
        .input-group input:focus, 
        .input-group textarea:focus {
            border-color: var(--pink);
            background-color: var(--light-pink);
        }

        .input-group textarea {
            height: 120px;
            resize: none;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--pink);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .submit-btn:hover {
            background-color: #e05281;
            transform: translateY(-2px);
        }

        .msg-status {
            margin-top: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="brand">OD.<span>SweetTreats</span></a>
        <div class="nav-right">
            <a href="index.php">Home</a>
            <a href="about.php">Our Story</a>
            <a href="cart.php" class="cart-btn">
                🛒 Bag (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)
            </a>
        </div>
    </div>
</nav>

<div class="contact-container">
    <h2>Get In Touch</h2>
    <form method="POST" action="">
        <div class="input-group">
            <label>Your Name</label>
            <input type="text" name="name" placeholder="Enter full name" required>
        </div>

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="email@example.com" required>
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="03xx-xxxxxxx" required>
        </div>

        <div class="input-group">
            <label>How can we help?</label>
            <textarea name="message" placeholder="Type your message here..." required></textarea>
        </div>

        <button type="submit" name="send_message" class="submit-btn">Send Message</button>
    </form>

    <?php
    if(isset($_POST['send_message'])){
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $message = htmlspecialchars($_POST['message']);

        try {
            $db->messages->insertOne([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'submitted_at' => new MongoDB\BSON\UTCDateTime()
            ]);
            echo "<div class='msg-status' style='color: green;'>Success! We will contact you soon.</div>";
        } catch (Exception $e) {
            echo "<div class='msg-status' style='color: red;'>Error saving message.</div>";
        }
    }
    ?>
</div>

<footer class="main-footer">
    <div class="footer-bottom">
        <p>&copy; 2026 SweetCreme. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>