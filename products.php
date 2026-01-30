
<?php
session_start();
require_once 'Product.php';
require_once 'Review.php';

$productObj = new Product();
$reviewObj  = new Review();

$id = intval($_GET['id'] ?? 0);
$product = $productObj->getProductById($id);

if(!$product){
    echo "<h1>Product not found!</h1>";
    exit;
}

$reviews = $reviewObj->getReviewsByProductId($id);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['title']); ?></title>
<link rel="stylesheet" href="<?php echo $cssFile; ?>">
<link rel="stylesheet" href="products.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>


<nav>
<ul>
<li class="logo"><a href="#"><img src="Logo.png" alt="PawCare Logo">PawCare</a></li>
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
      <p class="subtitle"><?php echo htmlspecialchars($product['description']); ?></p>

      <div class="rating">
        <?php
          $stars = round($product['rating']);
          echo str_repeat('★', $stars).str_repeat('☆', 5-$stars);
        ?>
      </div>

      <hr>

      <p class="product-p"><?php echo number_format($product['price'],2); ?>€</p>

      <div class="stock"><?php echo htmlspecialchars($product['stock']); ?></div>

      <hr>

      <div class="qty-row">
        <span class="qty-label">QUANTITY</span>
        <div class="qty-box">
          <button id="qty-minus">-</button>
          <span id="qty-value">1</span>
          <button id="qty-plus">+</button>
        </div>
        <span class="subtotal">Subtotal: <strong><?php echo number_format($product['price'],2); ?>€</strong></span>
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


<div class="details">
  <h2 class="h2-title">Product Details</h2>
  <p class="p-details"><?php echo nl2br(htmlspecialchars($product['details'])); ?></p>
</div>

<div class="keyfeatures">
  <h2>Key Benefits</h2>
  <ul>
    <?php foreach(explode("\n",$product['key_features']) as $f): ?>
      <li>✔ <?php echo htmlspecialchars($f); ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<section class="testimonials">
  <h2>Customer Reviews</h2>

  <?php if(empty($reviews)): ?>
    <p class="no-reviews">No reviews yet.</p>
  <?php endif; ?>

  <?php foreach($reviews as $r): ?>
    <div class="testimonial-card">
      <div class="stars">
        <?php echo str_repeat('★',$r['rating']).str_repeat('☆',5-$r['rating']); ?>
      </div>
      <p><?php echo htmlspecialchars($r['comment']); ?></p>
      <p class="author">– <?php echo htmlspecialchars($r['user_name']); ?></p>
    </div>
  <?php endforeach; ?>
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
<script>
const minus = document.getElementById('qty-minus');
const plus  = document.getElementById('qty-plus');
const qty   = document.getElementById('qty-value');
const sub   = document.querySelector('.subtotal strong');
const price = <?php echo $product['price']; ?>;

plus.onclick = ()=> {
  qty.textContent++;
  sub.textContent = (price * qty.textContent).toFixed(2) + "€";
};
minus.onclick = ()=> {
  if(qty.textContent > 1){
    qty.textContent--;
    sub.textContent = (price * qty.textContent).toFixed(2) + "€";
  }
};
</script>

</body>
</html>

