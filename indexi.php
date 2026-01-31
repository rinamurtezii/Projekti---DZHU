<?php
session_start();
require_once 'DogSlider.php';

$slider = new DogSlider();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCare - Home</title>
    <link rel="stylesheet" href="style.css">
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
        <a href="login.php"><button type="button">Sign In</button></a>
    </ul>
</nav>

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

<section class="home">
    <div class="teksti">
        <h1><b>Every Dog Deserves a Home — Maybe Yours?</b></h1>
        <p>Choose adoption. Change a life forever.</p>
        <a href="adopt.php"><button id="butoni" type="submit">Get Started!</button></a>
    </div>
    <div class="imazhi">
        <img id="imazhi1" src="foto1.jpg">
    </div>
</section>

<section class="ab">
    <h2>Who We Are</h2>
    <p class="pershkrimi">
        We are a dedicated organization committed to finding loving homes for dogs in need.
        Our mission is to connect compassionate individuals with their new best friends
        and give every dog a chance for a happy, fulfilling life.
    </p>
    <div class="stats">
        <div class="box">
            <p class="nr">500+</p>
            <p class="pershkrimitjt">Successful Adoptions</p>
        </div>
        <div class="box">
            <p class="nr">120+</p>
            <p class="pershkrimitjt">Pets Available Now</p>
        </div>
        <div class="box">
            <p class="nr">24/7</p>
            <p class="pershkrimitjt">Support & Care</p>
        </div>
    </div>
</section>


<section class="dogsNew">
  <h2 class="dogsNew-title">Meet Our Dogs</h2>
  <div class="dogsNew-wrap">
    <button class="dogsNew-arrow dogsNew-left" id="dogsLeft" type="button" aria-label="Previous">‹</button>
    <div class="dogsNew-viewport" id="dogsViewport">
      <div class="dogsNew-track" id="dogsTrack">
        <?= $slider->renderSlider() ?>
      </div>
    </div>
    <button class="dogsNew-arrow dogsNew-right" id="dogsRight" type="button" aria-label="Next">›</button>
  </div>
  <div class="dogsNew-progress">
    <div class="dogsNew-bar" id="dogsBar"></div>
  </div>
</section>

<section class="adopt-hero">
    <h2 class="adopt-title">Ready to Change a Life?</h2>
    <p class="adopt-text">Every adoption makes a difference. Start your journey to finding the perfect companion today.</p>
    <a href="adopt.php"><button class="adopt-btn" type="submit">Start Your Adoption Journey</button></a>
</section>

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
            <p> &copy; 2025 PawCare. All rights reserved. Made with love for pets.</p>
        </div>
    </div>
</footer>

<script src="cart.js"></script>
<script src="dogs-slider.js"></script>
</body>
</html>
