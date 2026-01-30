<?php 
session_start();
require_once 'Product.php';
require_once 'Review.php';

if(!isset($_GET['id'])){
die("Product not found");
}

$id=intval($_GET['id']);

$productObj = new Product();
$reviewObj = new Review();

$product = $productObj->getProductById($id);
if (!$product) {
    die("Product not found");
}

$reviews = $reviewObj->getReviewsByProduct($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ropetoy.css">
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

<div class="product-page">
  <div class="product-layout">

    <div class="product-media">
      <?php if(!empty($product['tag'])): ?>
            <span class="badge"><?php echo htmlspecialchars($product['tag']); ?></span>
        <?php endif; ?>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
    </div>

    <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
        <p class="subtitle"><?php echo htmlspecialchars($product['subtitle']); ?></p>
        <div class="rating">
            <?php 
            $stars = round($product['rating'] ?? 5);
            echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
            ?>
        </div>

      <hr>

        <p class="product-p"><?php echo number_format($product['price'], 2); ?>€</p>
        <div class="stock"><?php echo htmlspecialchars($product['stock']); ?></div>

      <hr>
      <div class="qty-row">
        <span class="qty-label">QUANTITY</span>
        <div class="qty-box">
            <button type="button" class="qty-btn" id="qty-minus">-</button>
            <span id="qty-value">1</span>
            <button type="button" class="qty-btn" id="qty-plus">+</button>
        </div>
        <span class="subtotal">Subtotal: <strong><?php echo number_format($product['price'], 2); ?>€</strong></span>
    </div>

      <button class="buy-btn add-to-cart-btn"
            data-name="<?php echo htmlspecialchars($product['title']); ?>"
            data-price="<?php echo $product['price']; ?>"
            data-img="<?php echo htmlspecialchars($product['image']); ?>">
            Add to Cart
            </button>
    </div>
  </div>
</div>

<br><br>
<div class="details">
    <h2>Product Details</h2>
    <p><?php echo nl2br(htmlspecialchars($product['details'])); ?></p>
</div>
<br><br><br>

<div class="keyfeatures">
    <h2>Key Features</h2>
    <ul>
        <?php foreach(explode("\n", $product['key_features']) as $feature): ?>
            <li><?php echo htmlspecialchars($feature); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<section class="testimonials">
    <h2 class="testimonials-title">Customer Reviews</h2>
    <div class="testimonials-row">
        <?php if(count($reviews) === 0): ?>
            <p>No reviews yet for this product.</p>
        <?php endif; ?>
        <?php foreach($reviews as $rev): ?>
            <div class="testimonial-card">
                <div class="stars">
                    <?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']); ?>
                </div>
                <p class="testimonial-text"><?php echo htmlspecialchars($rev['comment']); ?></p>
                <p class="testimonial-name">- <?php echo htmlspecialchars($rev['user_name']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<br><br>
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
<script src="shop.js"></script>
<script src="cart.js"></script>
<script>
const qtyMinus = document.getElementById('qty-minus');
const qtyPlus = document.getElementById('qty-plus');
const qtyValue = document.getElementById('qty-value');
const subtotal = document.querySelector('.subtotal strong');
const price = parseFloat(document.querySelector('.add-to-cart-btn').dataset.price);

qtyPlus.addEventListener('click', () => {
    qtyValue.textContent = parseInt(qtyValue.textContent) + 1;
    subtotal.textContent = (price * parseInt(qtyValue.textContent)).toFixed(2) + '€';
});

qtyMinus.addEventListener('click', () => {
    let qty = parseInt(qtyValue.textContent);
    if(qty > 1) {
        qtyValue.textContent = qty - 1;
        subtotal.textContent = (price * (qty - 1)).toFixed(2) + '€';
    }
});
</script>
</body>
</html>