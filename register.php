<?php 
session_start();
require 'DataBase.php';
require 'Users.php';

$db = new DataBase();
$pdo = $db->startConnection();

$userObj = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if($password !== $confirmPassword){
        $error = "Passwords do not match!";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters!";
    } else {
        $success = $userObj->register($name, $email, $password);
        if($success){
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'user';
            header("Location: login.php");
            exit;
        } else {
            $error = "Email already exists!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
 <nav>
        <ul>
        <li class="logo"><a href="#">
            <img src="Logo.png" alt="PawCare Logo">PawCare
        </a></li>
        <li><a href="indexi.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="adopt.php">Adopt</a></li>
        <li><a href="newsletter.php">Newsletter</a></li>
    <li class="cart-li">
    <a href="#" id="cartBtn" class="cart-icon">
    <img src="cart-white.png" alt="Cart" class="cart-img">
    <span id="cartCount" class="cart-count">0</span>
    </a>
    </li>
       <a href="login.php">
        <button type="button">Sign In</button>
    </a>
        </ul>
    </nav>
<br><br>
  
<div id="cartOverlay" class="cart-overlay"></div>
<div id="cartPanel" class="cart-panel">
  <div class="cart-head">
    <h3>Your Cart</h3>
    <button id="cartClose" type="button" class="cart-close">✕</button>
  </div>

  <div id="cartItems" class="cart-items"></div>

  <div class="cart-foot">
    <div class="cart-total">
      <span>Total:</span>
      <b><span id="cartTotal">0.00</span>€</b>
    </div>
    <button id="clearCart" class="cart-clear" type="button">Clear</button>
  </div>
</div>


  <div class="kryesorja">
    <div class="wrap">
      <div class="l" >
          <img src="Logo.png">
        </div>

        <h1 class="app">PawCare</h1>
        <p class="lead">Your journey to finding a furry friend</p>
      </div>
      <main class="card">
        <h2 id="signin">Create An Account</h2>
        <p class="shkurt">Join us and find your perfect companion</p>

          <?php
            if(isset($error)){
                echo "<p style='color:red; text-align:center;'>$error</p>";
            }
            if(isset($success)){
                echo "<p style='color:green; text-align:center;'>Account created successfully!</p>";
            }
            ?>

        

        <form class="forma" action="register.php" method="POST">
            <div class="fushat">
            <label for="email">Full Name</label>
            <input id="name" name="name" type="text" placeholder="Name">
          </div>

          <div class="fushat">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="you@example.com">
          </div>

          <div class="fushat">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="••••••••">
          </div>

          <div class="fushat">
            <label for="password">Confirm Password</label>
            <input id="confirmPassword" name="confirmPassword" type="password" placeholder="••••••••">
          </div>
          <br>
          <button class="btn" type="submit">Create Account</button>

          <div class="note">Already have an account?<a href="login.php" class="link-accent">Sign in</a></div>
        </form>
      </main>

    </div>



<footer>
    <div class="kuti">

    <div class="majtas">
        <div class="rreshti">
    <img id="imfoot" src="Logo.png" >
    <p class="emri">PawCare</p>
</div>
<p class="shkrimi">Connecting loving families with pets who need a forever home.</p><br>
<div class="ikonat">
    <img src="instagram.png">
    <img src="facebook.png">
    <img src="linkedin.png">
</div>
</div>
<div class="kolonapar">
    <p class="titullipar"><b>Quick Links:</b></p>
    <ul>
        <li><a href="indexi.php" style="text-decoration: none; color: inherit;">Home</a></li>
        <li><a href="about.php" style="text-decoration: none; color: inherit;">About</a></li>
        <li><a href="services.php" style="text-decoration: none; color: inherit;">Services</a></li>
        <li><a href="adopt.php" style="text-decoration: none; color: inherit;">Adopt</a></li>
        <li><a href="newsletter.php" style="text-decoration: none; color: inherit;">Newsletter</a></li>
    </ul>
</div>
    <div class="kolonadyt">
    <p class="titullidyt"><b>Contact Us:</b></p>
    <ul>
    <li>📞(111)123-4567</li>
    <li>✉️ info@pawcare.com</li>
    <li>📍123 Street, Animal City, XK</li>
</ul>
</div>
<div class="kolonatret">
    <p class="titullitret">Visit Us</p>
    
    <ul>
        <li><b>Hours:</b></li>
    <li>Mon-Fri: 10am-6pm</li>
    <li>Sat-Sun: 11am-5pm</li>
   </ul>
    </div>
<div class="fundi">
    <p> &copy: 2025 PawCare. All rights reserved.Made with love for pets.</p>
</div>
</div>
</footer>
<script src="cart.js"></script>
<script src="validatecreateaccount.js"></script>
</body>
</html>