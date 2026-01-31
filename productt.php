<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'Product.php';

class ProductItem {
    public $id, $title, $description, $price, $category, $image, $tag, $details, $features;

    public function __construct($data) {
        foreach($data as $k => $v) $this->$k = $v;
    }

    public function renderHeader() {
        return "
        <div class='product-media'>
            <span class='badge'>Popular</span>
            <img src='".htmlspecialchars($this->image)."' alt='".htmlspecialchars($this->title)."'>
        </div>
        <div class='product-info'>
            <h1 class='product-title'>".htmlspecialchars($this->title)."</h1>
            <p class='subtitle'>".htmlspecialchars($this->description)."</p>
            <div class='rating'>".str_repeat('★', 5)." (5.0)</div>
            <hr>
            <p class='product-p'>".number_format($this->price,2)."€</p>
            <br>
            <div class='stock'>● In Stock – Ready to Ship</div>
            <hr>
            <div class='qty-row'>
                <span class='qty-label'>QUANTITY</span>
                <div class='qty-box'>
                    <button type='button' class='qty-btn' id='qty-minus'>-</button>
                    <span id='qty-value'>1</span>
                    <button type='button' class='qty-btn' id='qty-plus'>+</button>
                </div>
                <span class='subtotal'>
                    Subtotal:
                    <strong>".number_format($this->price,2)."€</strong>
                </span>
            </div>
            <button class='buy-btn add-to-cart-btn'
                data-name='".htmlspecialchars($this->title)."'
                data-price='".$this->price."'
                data-img='".htmlspecialchars($this->image)."'>
                Add to Cart
            </button>
        </div>";
    }

    public function renderDetails() {
        return "
        <div class='details'>
            <h2 class='h2-title'>Product Details</h2>
            <p class='p-details'>".nl2br(htmlspecialchars($this->details))."</p>
        </div>";
    }

    public function renderFeatures() {
        $html = "<div class='keyfeatures'><h2>Key Features</h2><br><ul>";
        if (!empty($this->features)) {
            $features = explode(',', $this->features);
            foreach($features as $f) {
                $html .= "<li><img src='check.png' width='20' height='20'> ".htmlspecialchars(trim($f))."</li>";
            }
        }
        $html .= "</ul></div>";
        return $html;
    }
}
if (!isset($_GET['id'])) die("Product not found");
$id = intval($_GET['id']);

$productObj = new Product();
$productData = $productObj->getProductById($id);

if (!$productData) die("Product not found");

$product = new ProductItem($productData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->title) ?> | PawCare</title>
    <link rel="stylesheet" href="collar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="index.php"><img src="Logo.png" alt="PawCare Logo">PawCare</a></li>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="adopt.php">Adopt</a></li>
        <li><a href="newsletter.php">Newsletter</a></li>
        <li class="cart-li"><a href="#" id="cartBtn" class="cart-icon">
            <img src="cart-white.png" alt="Cart" class="cart-img">
            <span id="cartCount" class="cart-count">0</span>
        </a></li>
        <a href="login.html"><button type="button">Sign In</button></a>
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

<div class="product-page">
    <div class="product-layout">
        <?= $product->renderHeader() ?>
    </div>
</div>

<br><br>

<?= $product->renderDetails() ?>
<br><br>
<?= $product->renderFeatures() ?>

<br><br>

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
        <div class="kolonapar"><p class="titullipar"><b>Quick Links:</b></p>
            <ul><li>Home</li><li>About</li><li>Services</li><li>Adopt</li></ul>
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

<script src="shop.js"></script>
<script src="cart.js"></script>
</body>
</html>
