<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "DataBase.php";
require_once "NewsletterModel.php";

if (!isset($_GET['id'])) {
    die("Article not found");
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    die("Article not found");
}

$db = new DataBase();
$conn = $db->startConnection();

$model = new NewsletterModel($conn);


$article = $model->getNewsById($id);
if (!$article) {
    die("Article not found");
}

$moreStories = $model->getMoreStories($id, 3);
$cssMap = [
    1 => "cocos-journey.css",
    2 => "picture-day.css",
    3 => "not-well.css",
    4 => "exercise.css",
    5 => "spa.css",
];

$pageCss = $cssMap[$id] ?? "article.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($article['title'] ?? 'Article') ?> | PawCare</title>

  <link rel="stylesheet" href="<?= htmlspecialchars($pageCss) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li class="logo">
            <a href="indexi.php">
                <img src="Logo.png" alt="PawCare Logo">PawCare
            </a>
        </li>

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

        <?php if(isset($_SESSION['user_id'])): ?>

            <?php if(($_SESSION['user_role'] ?? 'user') === 'admin'): ?>
                <li>
                    <a href="admin_dashboard.php">
                        <button type="button">Admin Dashboard</button>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="account.php">
                        <button type="button">My Account</button>
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="logout.php">
                    <button type="button">Logout</button>
                </a>
            </li>

        <?php else: ?>

            <li>
                <a href="login.php">
                    <button type="button">Sign In</button>
                </a>
            </li>

        <?php endif; ?>
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

<a href="newsletter.php" class="back-arrow">← Back to News</a>

<div class="article-layout">
  <div class="article-container">

    <h2 class="article-title"><?= htmlspecialchars($article['title'] ?? '') ?></h2>

    <div class="article-meta">
      <span>By PawCare Team</span> •
      <span>
        <?= !empty($article['created_at']) ? date("F Y", strtotime($article['created_at'])) : "" ?>
      </span>
    </div>

    <?php if (!empty($article['image'])): ?>
      <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title'] ?? '') ?>" class="article-image">
    <?php endif; ?>

  <?php
$content = trim((string)($article['content'] ?? ''));
$summary = trim((string)($article['summary'] ?? ''));

if ($content !== '') {
    echo $content; 
} else {
    echo "<p>" . htmlspecialchars($summary, ENT_QUOTES, 'UTF-8') . "</p>";
}
?>
  </div>

  <aside class="article-sidebar">
    <h3>More Stories</h3>

    <?php foreach ($moreStories as $s): ?>
      <div class="sidebar-item">
        <a href="article.php?id=<?= (int)$s['id'] ?>">
          <img src="<?= htmlspecialchars($s['image'] ?? '') ?>" alt="">
          <div class="sidebar-text">
            <h4><?= htmlspecialchars($s['title'] ?? '') ?></h4>
            <p><?= htmlspecialchars($s['summary'] ?? '') ?></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>

    <div class="sidebar-quote">“Every rescue tells a story worth sharing.”</div>
  </aside>
</div>

<footer>
  <div class="kuti">
    <div class="majtas">
      <div class="rreshti">
        <img id="imfoot" src="Logo.png">
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
      <p>&copy: 2025 PawCare. All rights reserved.Made with love for pets.</p>
    </div>
  </div>
</footer>

<script src="cart.js"></script>
</body>
</html>
