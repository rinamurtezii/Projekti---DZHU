<?php 
session_start();
require_once 'Product.php';
require_once 'Review.php';

$productObj= new Product();
$reviewObj= new Review();

$products = $productObj->getAllProducts();
$reviews = $reviewObj->getAllReviews();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="services.css">
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
        <li><a href="indexi.html">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="adopt.html">Adopt</a></li>
        <li><a href="newsletter.html">Newsletter</a></li>
        <li class="cart-li">
        <a href="#" id="cartBtn" class="cart-icon">
        <img src="cart-white.png" alt="Cart" class="cart-img">
        <span id="cartCount" class="cart-count">0</span>
        </a>
        </li>
        <a href="login.html"><button type="button">Sign In</button></a>
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
<br><br>


<section class="our-shop">
  <h1>Our Shop</h1>
  <p>Everything you need for your furry companion</p>
</section>
<br><br><br>

<section class="shop">
  <div class="features">

    <div class="feature-card">
        <img src="delivery.png" alt="Free Shipping Icon" class="feature-img">
      <div class="feature-text">
        <h3>Free Shipping</h3>
        <p>On orders over $50</p>
      </div>
    </div>

    <div class="feature-card">
        <img src="protected.png" alt="Guarantee Icon" class="feature-img">
      <div class="feature-text">
        <h3>Money-Back Guarantee</h3>
        <p>30-day returns</p>
      </div>
    </div>

    <div class="feature-card">
        <img src="time.png" alt="Fast Delivery Icon" class="feature-img">
      <div class="feature-text">
        <h3>Fast Delivery</h3>
        <p>2–3 business days</p>
      </div>
    </div>

  </div>
</section>
<br><br>

<br><br>
<?php 
// Grupi produktet sipas kategorive
$categories = [];
foreach ($products as $p) {
    $cat = trim($p['category']);
    $categories[$cat][] = $p;
}

// Loop për secilën kategori
foreach ($categories as $categoryName => $categoryProducts):
?>
<div class="section-title">
    <div class="title-icon">
        <?php
        $cleanName = strtolower(trim($categoryName));
        if (strpos($cleanName, 'dog toys & entertainment') !== false) {
            echo '<img src="bone.png" alt="Dog Toys & Entertainment">';
        } elseif (strpos($cleanName, 'nutritious dog food') !== false) {
            echo '<img src="heart.png" alt="Nutritious Dog Food">';
        } else {
            echo '<img src="parcel.png" alt="Other">';
        }
        ?>
    </div>
    <h2><?php echo htmlspecialchars($categoryName); ?></h2>
</div>

<section class="products">
    <?php foreach ($categoryProducts as $product): ?>
    <div class="product-card">
        <div class="img-box">
            <?php if (!empty($product['tag'])): ?>
                <span class="tag <?php echo strtolower($product['tag']); ?>">
                    <?php echo htmlspecialchars($product['tag']); ?>
                </span>
            <?php endif; ?>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
        </div>
        <div class="info">
            <div class="rating">
                <?php
                $stars = round($product['rating'] ?? 5); // default 5 if null
                echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
                ?>
            </div>
            <h3><?php echo htmlspecialchars($product['title']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="bottom">
                <span class="price"><?php echo number_format($product['price'], 2); ?>€</span>
                <!-- BUTONI BUY NOW -->
                <a href="productt.php?id=<?php echo $product['id']; ?>" class="buy-btn-link">
                    Buy Now
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<?php endforeach; ?>


<section class="testimonials">
    <h2 class="testimonials-title">What Our Customers Say</h2>
    <div class="testimonials-row">
        <?php foreach($reviews as $rev): ?>
        <div class="testimonial-card">
            <div class="stars">
                <?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']); ?>
            </div>
            <p class="testimonial-text"><?php echo htmlspecialchars($rev['comment']); ?></p>
            <p class="testimonial-name">- User #<?php echo $rev['user_id']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
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
        <li>Home</li>
        <li>About</li>
        <li>Services</li>
        <li>Adopt</li>
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
</body>
</html>