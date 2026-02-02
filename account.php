<?php
session_start();
require_once "DataBase.php";
require_once "Users.php";

require_once "AdoptionRequest.php";

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$db = new DataBase();
$pdo = $db->startConnection();

$userObj = new User($pdo);
$user = $userObj->getUserById($_SESSION['user_id']);

$reqObj = new AdoptionRequest($pdo);
$userId = (int)$_SESSION['user_id'];


if (isset($_GET['cancel'])) {
    $reqObj->cancelPending((int)$_GET['cancel'], $userId);
    header("Location: account.php");
    exit;
}

$editId = isset($_GET['editReq']) ? (int)$_GET['editReq'] : 0;
$editReq = null;
if ($editId > 0) {
    $editReq = $reqObj->getByIdForUser($editId, $userId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['req_id'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $reason    = trim($_POST['reason'] ?? '');

    $reqObj->updatePending((int)$_POST['req_id'], $userId, $full_name, $phone, $address, $email, $reason);

    header("Location: account.php");
    exit;
}

$myRequests = $reqObj->getAllByUser($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account - PawCare</title>
<link rel="stylesheet" href="account.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="indexi.php">
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
        <?php if(isset($_SESSION['user_id'])): ?>
    <li>
        <a href="account.php">
            <button type="button">My Account</button>
        </a>
    </li>
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

<div class="account-container">
    <h1>Welcome, <?= e($user['name']) ?>!</h1>

    <div class="profile-info">
        <h2>Profile Information</h2>
        <p><b>Name:</b> <?= e($user['name']) ?></p>
        <p><b>Email:</b> <?= e($user['email']) ?></p>
        <p><b>Role:</b> <?= e($user['role']) ?></p>
        <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
    </div>

    <div class="profile-info" style="margin-top:22px;">
    <h2>Dogs you’ve applied for</h2>

    <?php if ($editReq): ?>
        <h3 style="margin-top:12px;">Edit Application</h3>

        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="req_id" value="<?= (int)$editReq['id'] ?>">

            <label>Full Name</label>
            <input name="full_name" required value="<?= e($editReq['full_name']) ?>">

            <label>Phone</label>
            <input name="phone" required value="<?= e($editReq['phone']) ?>">

            <label>Home Address</label>
            <input name="address" required value="<?= e($editReq['address']) ?>">

            <label>Email</label>
            <input name="email" type="email" required value="<?= e($editReq['email']) ?>">

            <label>Reason</label>
            <textarea name="reason" rows="4" required><?= e($editReq['reason']) ?></textarea>

            <button type="submit" class="edit-btn">Update</button>
            <a href="account.php" class="edit-btn" style="margin-left:10px;">Cancel</a>
        </form>
        <hr style="margin:18px 0;">
    <?php endif; ?>

    <?php if (empty($myRequests)): ?>
        <p>You haven’t submitted any adoption applications yet.</p>
    <?php else: ?>
        <table style="width:100%; margin-top:12px; border-collapse:collapse;">
            <tr>
                <th style="text-align:left; padding:10px;">Dog</th>
                <th style="text-align:left; padding:10px;">Status</th>
                <th style="text-align:left; padding:10px;">Actions</th>
            </tr>

            <?php foreach ($myRequests as $r): ?>
                <tr style="border-top:1px solid #eee;">
                    <td style="padding:10px; display:flex; gap:10px; align-items:center;">
                        <img src="<?= e($r['dog_image']) ?>" alt="<?= e($r['dog_name']) ?>"
                             style="width:55px; height:55px; object-fit:cover; border-radius:12px;">
                        <b><?= e($r['dog_name']) ?></b>
                    </td>

                    <td style="padding:10px;">
                        <?= e($r['status']) ?>
                    </td>

                    <td style="padding:10px;">
                        <?php if ($r['status'] === 'pending'): ?>
                            <a href="account.php?editReq=<?= (int)$r['id'] ?>">Edit</a>
                            |
                            <a href="account.php?cancel=<?= (int)$r['id'] ?>"
                               onclick="return confirm('Cancel this application?')">Cancel</a>
                        <?php else: ?>
                            <span style="opacity:.6;">No actions</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</div>

 
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

<script src="shop.js"></script>
<script src="cart.js"></script>

</body>
</html>
