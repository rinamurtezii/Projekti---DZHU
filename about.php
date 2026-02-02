<?php
session_start();
require_once 'Team.php';
require_once 'SuccessStories.php';

$successObj = new SuccessStories();
$stories = $successObj->getAll();

$teamObj = new Team();
$teamMembers = $teamObj->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="about.css">
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
    <section class="pawcare">
        <div class="pawcare-kutia">
            <div class="pawcare-foto">
                <img src="aboutfot.jpg">
            </div>

            <div class="pawcare-teksti">
                <h1>PawCare</h1>
                <p>PawCare was founded in 2018 by a small group of volunteers and veterinary professionals
                who shared one mission: to give every abandoned pet a chance at a better life. Over the years,
                PawCare has built strong partnerships with shelters, trainers, and foster families, allowing us
                to rescue hundreds of pets in need.</p>
           
                <p>Our work is guided by compassion, transparency, and the belief that every pet deserves a loving
                home. Today, PawCare continues to expand its impact, educating the public and supporting responsible
                pet ownership.</p>

                <div class="pawcare-ikonat">
                    <div class="ikona-kutia">
                        <div class="ikona">
                            <img src="ikona1.1.png">
                        </div>
                        <p>Excellence</p>
                    </div>
                    <div class="ikona-kutia">
                        <div class="ikona">
                            <img src="ikona1.2.png">
                        </div>
                        <p>Compassion</p>
                    </div>
                    <div class="ikona-kutia">
                        <div class="ikona">
                            <img src="ikona 1.3.png">
                        </div>
                        <p>Dedication</p>
                    </div>
                </div>
            </div>

        </div>

    </section>


<div class="mission-vision-container">
    <div class="card">
        <h2>Our Mission</h2>
        <p>
            To rescue, rehabilitate, and rehome dogs in need while promoting responsible pet ownership 
            and creating lasting bonds between pets and their families. We strive to ensure every dog 
            receives the love, care, and second chance they deserve.
        </p>
    </div>
    <div class="card">
        <h2>Our Vision</h2>
        <p>
            A world where every dog has a loving home and no animal is left behind. We envision 
            communities where adoption is the first choice, and every pet owner has access to the 
            resources and support they need to provide the best care possible.
        </p>
    </div>
</div><br>


<section class="sektoriiekipes">
    <h2>Meet Our Team</h2>
    <p>We’re more than an organization — we’re a family. Meet the people who help dogs find theirs.</p>

    <div class="Puntoret">
        <?php foreach ($teamMembers as $member): ?>
            <div class="teamc">
                <div class="team-photo">
                    <img src="<?= $member['photo']; ?>">
                </div>
                <h3><?= $member['name']; ?></h3>
                <p><?= $member['role']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="success-stories">
    <h2 class="success-title">Success Stories</h2>
    <p class="success-subtitle">Heartwarming tales from families who found their perfect companions through PawCare</p>
    <div class="stories-grid">
        <?php foreach($stories as $story): ?>
            <div class="story-card">
                <img src="<?= $story['photo'] ?>" alt="<?= $story['name'] ?>" class="story-avatar">
                <div class="story-info">
                    <h3 class="story-name"><?= $story['name'] ?></h3>
                    <p class="story-pet">Adopted <b><?= $story['pet_name'] ?></b></p>
                    <p class="story-text"><?= $story['story'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<br>
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
</body>
</html>