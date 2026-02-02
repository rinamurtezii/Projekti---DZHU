<?php
session_start();
require 'DataBase.php';
require 'Users.php';

$db = new DataBase();
$pdo = $db->startConnection();

$userObj = new User($pdo);

if(isset($_SESSION['user_id'])) {
    header("Location: indexi.php"); // ose dashboard.php
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if($email === '' || $password === ''){
        $error = "Please fill in all fields!";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Please enter a valid email!";
    }else{
        $user = $userObj->login($email, $password);

    if($user){
    $_SESSION['user_id'] = $user['id'] ?? null;
    $_SESSION['user_name'] = $user['name'] ?? 'User';
    $_SESSION['user_role'] = $user['role'] ?? 'user';

    $return = $_GET['return'] ?? 'indexi.php';

    if($_SESSION['user_role'] === 'admin') {
        header("Location: admin_dashboard.php");
    }else{
        header("Location: " . $return);
    }
    exit;
        }else{
            $error = "Invalid email or password!";
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
    <link rel="stylesheet" href="login.css">
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
        <button type="button">Sign In</button>
        </ul>
    </nav>
    <br>

     
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
        <h2 id="signin">Sign In</h2>
        <p class="shkurt">Welcome back! Please sign in to continue</p>

        <?php
        if (!empty($_SESSION['flash_error'])) {
          echo "<p style='color:red; text-align:center;'>" . htmlspecialchars($_SESSION['flash_error']) . "</p>";
          unset($_SESSION['flash_error']);
        }

        if(isset($error)){
            echo "<p style='color:red; text-align:center;'>$error</p>";
        }
        ?>

        <form class="forma" action="login.php" method="POST">
          <div class="fushat">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="you@example.com" >
          </div>

          <div class="fushat">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="••••••••" >
          </div>

          <div class="forgot">
            <a href="forgotpassword.html" class="linktjt">Forgot password?</a>
          </div>

          <button class="btn" type="submit">Sign In</button>

          <div class="note">Don't have an account? <a href="register.php" class="link-accent">Create one</a></div>
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
<script src="validatelogin.js"></script>
<script src="cart.js"></script>
</body>
</html>