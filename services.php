<?php
session_start();
require_once 'Product.php';
require_once 'Review.php';

class ProductItem {
    public $id, $title, $description, $price, $category, $image, $tag, $details, $features;

    public function __construct($data) {
        foreach($data as $k => $v) $this->$k = $v;
    }

    public function renderCard() {
        $tagHtml = $this->tag ? "<span class='tag ".strtolower($this->tag)."'>".htmlspecialchars($this->tag)."</span>" : "";
        $stars = str_repeat('★', 5); 
        return "
        <div class='product-card'>
            <div class='img-box'>
                $tagHtml
                <img src='".htmlspecialchars($this->image)."' alt='".htmlspecialchars($this->title)."'>
            </div>
            <div class='info'>
                <div class='rating'>$stars</div>
                <h3>".htmlspecialchars($this->title)."</h3>
                <p>".htmlspecialchars($this->description)."</p>
                <div class='bottom'>
                    <span class='price'>".number_format($this->price,2)."€</span>
                    <a href='productt.php?id={$this->id}' class='buy-btn-link'>Buy Now</a>
                </div>
            </div>
        </div>
        ";
    }
}

class Category {
    public $name;
    public $products = [];

    public function __construct($name) { $this->name = $name; }
    public function addProduct(ProductItem $p) { $this->products[] = $p; }

    public function renderSection() {
        $icon = 'parcel.png';
        $cleanName = strtolower(trim($this->name));
        if (strpos($cleanName, 'dog toys & entertainment') !== false) $icon = 'bone.png';
        elseif (strpos($cleanName, 'nutritious dog food') !== false) $icon = 'heart.png';

        $html = "<div class='section-title'>
                    <div class='title-icon'><img src='$icon' alt='".htmlspecialchars($this->name)."'></div>
                    <h2>".htmlspecialchars($this->name)."</h2>
                 </div>
                 <section class='products'>";
        foreach ($this->products as $p) $html .= $p->renderCard();
        $html .= "</section>";
        return $html;
    }
}

$productObj = new Product();
$productsData = $productObj->getAllProducts();
$productObjs = array_map(fn($p) => new ProductItem($p), $productsData);

$reviewObj = new Review();
$reviews= $reviewObj->getAllReviews();

$categories = [];
foreach ($productObjs as $prod) {
    $catName = $prod->category;
    if (!isset($categories[$catName])) $categories[$catName] = new Category($catName);
    $categories[$catName]->addProduct($prod);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCare Services</title>
    <link rel="stylesheet" href="services.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="#"><img src="Logo.png" alt="PawCare Logo">PawCare</a></li>
        <li><a href="indexi.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="adopt.php">Adopt</a></li>
        <li><a href="newsletter.php">Newsletter</a></li>
        <li class="cart-li"><a href="#" id="cartBtn" class="cart-icon">
            <img src="cart-white.png" alt="Cart" class="cart-img">
            <span id="cartCount" class="cart-count">0</span>
        </a></li>
        <a href="login.php"><button type="button">Sign In</button></a>
    </ul>
</nav>

<div id="cartOverlay" class="cart-overlay"></div>
<div id="cartPanel" class="cart-panel">
    <div class="cart-head"><h3>Your Cart</h3><button id="cartClose" type="button" class="cart-close">✕</button></div>
    <div id="cartItems" class="cart-items"></div>
    <div class="cart-foot">
        <div class="cart-total"><span>Total:</span><b><span id="cartTotal">0.00</span>€</b></div>
        <button id="clearCart" class="cart-clear" type="button">Clear</button>
    </div>
</div>
<br><br>

<section class="our-shop">
    <h1>Our Shop</h1>
    <p>Everything you need for your furry companion</p>
</section>
<br><br>

<?php
foreach ($categories as $category) echo $category->renderSection();
?>

<section class="testimonials">
    <h2 class="testimonials-title">What Our Customers Say</h2>
    <div class="testimonials-row">
        <?php foreach ($reviews as $rev): ?>
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

<footer>
    <div class="kuti">
        <div class="majtas">
            <div class="rreshti"><img id="imfoot" src="Logo.png"><p class="emri">PawCare</p></div>
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
        <div class="kolonadyt"><p class="titullidyt"><b>Contact Us:</b></p>
            <ul><li>📞(111)123-4567</li><li>✉️ info@pawcare.com</li><li>📍123 Street, Animal City, XK</li></ul>
        </div>
        <div class="kolonatret"><p class="titullitret">Visit Us</p>
            <ul><li><b>Hours:</b></li><li>Mon-Fri: 10am-6pm</li><li>Sat-Sun: 11am-5pm</li></ul>
        </div>
        <div class="fundi"><p>&copy; 2025 PawCare. All rights reserved.</p></div>
    </div>
</footer>

<script src="cart.js"></script>
</body>
</html>
