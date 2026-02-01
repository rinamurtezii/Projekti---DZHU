<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "DataBase.php";
require_once "DogModel.php";

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("Dog not found");

$db = new DataBase();
$conn = $db->startConnection();

$dogModel = new DogModel($conn);
$dog = $dogModel->getById($id);
if (!$dog) die("Dog not found");

$cssMap = [
  1 => 'rocky.css',
  2 => 'formulari.css',
  3 => 'nate.css',
  4 => 'jenny.css',
  5 => 'bowy.css',
  6 => 'ladi.css',
];

$cssFile = $cssMap[(int)$dog['id']];

$errors = [];
$success = false;

$full_name = trim($_POST['full_name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$address   = trim($_POST['address'] ?? '');
$email     = trim($_POST['email'] ?? '');
$reason    = trim($_POST['reason'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($full_name === '' || mb_strlen($full_name) < 3) $errors[] = "Full Name must be at least 3 characters";
  if ($phone === '' || !preg_match('/^[0-9+\-\s]{7,}$/', $phone)) $errors[] = "Phone Number is not valid";
  if ($address === '' || mb_strlen($address) < 5) $errors[] = "The home address provided is too short";
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email is not valid";
  if ($reason === '' || mb_strlen($reason) < 10) $errors[] = "Reason must be at least 10 characters";

  if (empty($errors)) {
    $stmt = $conn->prepare("
      INSERT INTO adoption_requests (dog_id, full_name, phone, address, email, reason, status)
      VALUES (:dog_id, :full_name, :phone, :address, :email, :reason, 'pending')
    ");
    $stmt->execute([
      ':dog_id'    => $dog['id'],
      ':full_name' => $full_name,
      ':phone'     => $phone,
      ':address'   => $address,
      ':email'     => $email,
      ':reason'    => $reason
    ]);

    header("Location: sukses.html");
    exit;


    $full_name = $phone = $address = $email = $reason = '';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adoption Application - <?= e($dog['name']) ?></title>
  <link rel="stylesheet" href="<?= e($cssFile) ?>">
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

<div class="forma-qeni">
  <div class="form-kutia">
    <h2>Adoption Application</h2>
    <p>Applying for <b><?= e($dog['name']) ?></b></p>

    <?php if (!empty($errors)): ?>
      <div style="background:#ffe3e3; padding:10px; border-radius:10px; margin-bottom:12px;">
        <ul style="margin:0; padding-left:18px;">
          <?php foreach($errors as $er): ?>
            <li><?= e($er) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="apply.php?id=<?= (int)$dog['id'] ?>">
      <label>Full Name</label>
      <input name="full_name" type="text" placeholder="Full Name" required value="<?= e($full_name) ?>">

      <label>Phone Number</label>
      <input name="phone" type="tel" placeholder="Phone Number" required value="<?= e($phone) ?>">

      <label>Home Address</label>
      <input name="address" type="text" placeholder="Home Address" required value="<?= e($address) ?>">

      <label>Email</label>
      <input name="email" type="email" placeholder="Email" required value="<?= e($email) ?>">

      <label>Why do you want to adopt this dog?</label>
      <textarea name="reason" placeholder="Write here.." rows="5" required><?= e($reason) ?></textarea>

      <div class="buttonat">
        <a href="adopt.php" class="back-btn">Back to Results</a>
        <button type="submit" class="submit-btn">Submit Application</button>
      </div>
    </form>
  </div>

  <div class="kartaqenit">
    <div class="fototwrap">
      <img src="<?= e($dog['image']) ?>">

      <div class="badges">
        <span><?= e($dog['energy']) ?> Energy</span>
        <span><?= e($dog['size']) ?></span>
      </div>
    </div>

    <h3><?= e($dog['name']) ?></h3>
    <p><?= e($dog['description']) ?></p>
    <p>📆<?= (int)$dog['age'] ?> Years Old</p>
  </div>
</div>

<script src="cart.js"></script>
</body>
</html>
