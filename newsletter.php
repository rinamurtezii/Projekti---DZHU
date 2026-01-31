<?php
require_once "DataBase.php";
require_once "NewsletterModel.php";

$db = new DataBase();
$conn = $db->startConnection();

$model = new NewsletterModel($conn);

$mainNews = $model->getNews(1, 2);
$sideNews = $model->getNews(0, 3);  
$events   = $model->getEvents(3); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="newsletter.css">
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

<section class="paw-hero">
  <div class="paw-hero__inner">
    <p class="paw-hero__pill">PawCare • Adoption & Rescue</p>

    <h1 class="paw-hero__title">
      Your weekly <i style="color: #ff8a3d;">PawCare</i> Newsletter — adoption stories, events, and caring tips.
    </h1>
  </div>
</section>


<div class="news-head">
  <h2 class="news-title">Inside PawCare</h2>
  <p class="news-sub">A closer look at our dogs, our days, and our mission.</p>
</div>

<section class="news-box">
  <div class="main-news">
    <?php if (!empty($mainNews)): ?>
      <?php foreach ($mainNews as $n): ?>
        <article class="news-card big">
          <img src="<?= htmlspecialchars($n['image']) ?>" alt="<?= htmlspecialchars($n['title']) ?>">

          <div class="content">
            <span class="date"><?= strtoupper(date("M d, Y", strtotime($n['created_at']))) ?></span>
            <h4><?= htmlspecialchars($n['title']) ?></h4>
            <p><?= htmlspecialchars($n['summary']) ?></p>
           <a href="article.php?id=<?= (int)$n['id'] ?>" class="read-more">Read Full Story →</a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="padding:10px;">No main news yet.</p>
    <?php endif; ?>
  </div>

  <aside class="side-news">
    <?php if (!empty($sideNews)): ?>
      <?php foreach ($sideNews as $n): ?>
        <article class="side-item">
          <img src="<?= htmlspecialchars($n['image']) ?>" alt="<?= htmlspecialchars($n['title']) ?>">

          <div>
            <?php if (!empty($n['read_time'])): ?>
              <span class="read-time"><?= htmlspecialchars($n['read_time']) ?></span>
            <?php endif; ?>

            <a href="article.php?id=<?= (int)$n['id'] ?>" class="img-link">
              <h4><?= htmlspecialchars($n['title']) ?></h4>
              <p><?= htmlspecialchars($n['summary']) ?></p>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="padding:10px;">No side news yet.</p>
    <?php endif; ?>
  </aside>
</section>


<section class="events">
  <h2>Our Upcoming Events</h2>

  <div class="cards">
    <?php if (!empty($events)): ?>
      <?php foreach ($events as $e): ?>
        <div class="card">
          <div class="icon"><?= htmlspecialchars($e['icon']) ?></div>
          <h3><?= htmlspecialchars($e['title']) ?></h3>
          <p class="meta"><?= htmlspecialchars($e['meta']) ?></p>
          <p><?= htmlspecialchars($e['description']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="padding:10px;">No events yet.</p>
    <?php endif; ?>
  </div>
</section>


<section class="fact-section">
    <div class="fact-card">
        <div class="fact-icon">✨</div>

        <span class="fact-label">FACT OF THE DAY</span>

        <p class="fact-text">
            “Dogs can get jealous! If you pet another dog, your pup might try to steal your attention.”
        </p>
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
</body>
</html>