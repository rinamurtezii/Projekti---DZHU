<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "DataBase.php";
require_once "DogModel.php";

$db = new DataBase();
$conn = $db->startConnection();

$model = new DogModel($conn);
$dogs = $model->getDogs();

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt</title>
    <link rel="stylesheet" href="adopt.css">
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

    <section class="adoptfilter">
        <h2>Find Your Perfect Companion</h2>
        <p>Browse our available dogs and find your new best friend. Each one is waiting for a loving home.</p>
    </section>

    <section class="filterbox">
        <h3>Filter Pets</h3>

        <div class="filtergrupet">
            <label for="sizeFilter">Size</label>
            <select id="sizeFilter" name="size">
                <option value="all">All Sizes</option>
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
            </select>
        </div>
        <button class="resetbutoni" type="button">Reset Filters</button>
    </section>

<section class="qent">
  <div class="qent-kutia">

    <?php if (empty($dogs)): ?>
      <p style="padding:20px;">Nuk ka qen me status <b>available</b> në databazë.</p>
    <?php else: ?>
      <?php foreach ($dogs as $d): ?>
        <div class="kartaqenit" data-size="<?= e($d['size']) ?>">
          <div class="fototwrap">
            <img src="<?= e($d['image']) ?>" alt="<?= e($d['name']) ?>">

            <div class="badges">
              <span><?= e($d['energy']) ?> Energy</span>
              <span><?= e($d['size']) ?></span>
            </div>
          </div>

          <h3><?= e($d['name']) ?></h3>
          <p><?= e($d['description']) ?></p>
          <p>📆<?= (int)$d['age'] ?> Years Old</p>

          <a href="apply.php?id=<?= (int)$d['id'] ?>">
            <button class="adopt-btn">Adopt Now!</button>
          </a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
</section>


    <section class="procesiadoption">
        <h2>The Adoption Process </h2>
        <p>Our straightforward process ensures the perfect match between pets and families.</p>
        <div class="kolonat">
            <div class="kolona">
                <img src="browse.png">
                <h3>1. Browse & Choose</h3>
                <p>Explore our available dogs online and find the one that matches you.</p>
            </div>
            <div class="kolona">
                <img src="submit.png">
                <h3>2. Submit Application</h3>
                <p>Fill out our adoption application form. We'll review it within 24-48hours.</p>
            </div>
            <div class="kolona">
                <img src="meet.png">
                <h3>3. Take Them Home</h3>
                <p>Complete the adoption process and welcome your new companion home!</p>
            </div>
        </div>
        <br><br>
    </section>

    <section class="req">
        <div class="req-header">
            <h1>Adoption Requirements</h1>
            <p>We want to ensure every adoption is successful for both dog and family.</p>
        </div>
        <div class="req-box">

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Age Requirement</h3>
                    <p>You must be at least 18 years old to adopt our dogs.</p>
                </div>
            </div>

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Valid Identification</h3>
                    <p>Bring a government-issued photo ID to your visit. This helps us confirm your identity and ensures a safe and responsible adoption process.</p>
                </div>
            </div>

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Landlord Approval</h3>
                    <p>If renting, provide written permission from your landlord to have a dog.</p>
                </div>
            </div>

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Family Agreement</h3>
                    <p>All household members must be on board with the adoption decision.</p>
                </div>
            </div>

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Financial Ability</h3>
                    <p>Demonstrate ability to provide proper food, medical care, and supplies. Owning a pet is a long-term commitment that requires responsible budgeting.</p>
                </div>
            </div>

            <div class="req-item">
                <img src="checki.adopt.png" alt="check">
                <div>
                    <h3>Time Commitment</h3>
                    <p>Be prepared to dedicate time for training, exercise, and companionship.</p>
                </div>
            </div>

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

   <script>
document.addEventListener("DOMContentLoaded", () => {
  const sizeFilter = document.getElementById("sizeFilter");
  const resetBtn = document.querySelector(".resetbutoni");

  function filterDogs() {
    const selectedSize = sizeFilter.value;
    const cards = document.querySelectorAll(".kartaqenit");

    cards.forEach(card => {
      const cardSize = card.dataset.size; 

      if (selectedSize === "all" || cardSize === selectedSize) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }

  sizeFilter.addEventListener("change", filterDogs);

  resetBtn.addEventListener("click", () => {
    sizeFilter.value = "all";
    filterDogs();
  });

  filterDogs();
});
</script>

    <script src="cart.js"></script>
</body>
</html>
