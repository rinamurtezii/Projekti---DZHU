<?php
require_once "admin_guard.php"; 
require_once "DataBase.php";
require_once "Users.php";
require_once 'Review.php';
require_once 'Product.php';
require_once 'Team.php';

$db = new Database();
$pdo = $db->startConnection();
$userObj = new User($pdo);
$reviewObj = new Review();
$productObj = new Product();
$teamObj = new Team();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$message = '';
$error = '';
$reviewMessage = '';
$reviewError = '';
$editUser = null;
$editReview = null;
$editProduct = null;
$editMember = null;

// ===================== USERS CRUD =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'create' || $action === 'update')) {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';

    if ($action === 'create') {
        if ($userObj->register($name, $email, $password, $role)) {
            $message = "User created successfully";
        } else {
            $error = "Email already exists";
        }
    }

    if ($action === 'update' && $id > 0) {
        if (!$userObj->updateNameEmail($id, $name, $email)) {
            $error = "Email already exists for another user";
        } else {
            $userObj->updateRole($id, $role);
            if(!empty($password)){
                $userObj->updatePassword($id, $password);
            }
            $message = "User updated successfully";
        }
    }
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($userObj->deleteUser($id)) {
        $message = "User deleted successfully";
    } else {
        $error = "You cannot delete this user";
    }
}

if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    $editUser = $userObj->find($id);
}

// ===================== REVIEWS CRUD =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'update')) {
    $id = (int)($_POST['id'] ?? 0);
    $product_id = (int)($_POST['product_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($action === 'add') {
        if ($reviewObj->addReview($product_id, $user_id, $rating, $comment)) {
            $reviewMessage = "Review added successfully.";
        } else {
            $reviewError = "Failed to add review.";
        }
    }

    if ($action === 'update' && $id > 0) {
        $updated_by = $_SESSION['user_id'] ?? 0;
        if ($reviewObj->updateReview($id, $rating, $comment, $updated_by)) {
            $reviewMessage = "Review updated successfully.";
        } else {
            $reviewError = "Failed to update review.";
        }
    }
}

if (isset($_GET['action'])) {
    $id = (int)($_GET['id'] ?? 0);

    if ($action === 'delete' && $id > 0) {
        if ($reviewObj->deleteReview($id)) {
            $reviewMessage = "Review deleted successfully.";
        } else {
            $reviewError = "Failed to delete review.";
        }
    }

    if ($action === 'edit' && $id > 0) {
        $editReview = $reviewObj->find($id);
        $editProduct = $productObj->find($id);
        $editMember = $teamObj->find($id);
    }
}

// ===================== PRODUCTS =====================
$products = $productObj->getAllProducts();
if(isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit'){
    $editProduct = $productObj->find((int)$_GET['id']);
}

// ===================== TEAM =====================
$teamMembers = $teamObj->getAll();
if(isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit'){
    $editMember = $teamObj->find((int)$_GET['id']);
}

// ===================== GET REVIEWS & USERS =====================
$reviews = $reviewObj->getAllReviews();
$users = $userObj->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PawCare - Admin</title>
<link rel="stylesheet" href="admin.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li class="logo"><a href="indexi.php"><img src="Logo.png" alt="PawCare Logo">PawCare</a></li>
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
                <li><a href="admin_dashboard.php"><button type="button">Admin Dashboard</button></a></li>
            <?php else: ?>
                <li><a href="account.php"><button type="button">My Account</button></a></li>
            <?php endif; ?>
            <li><a href="logout.php"><button type="button">Logout</button></a></li>
        <?php else: ?>
            <li><a href="login.php"><button type="button">Sign In</button></a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
<?php
$section = $_GET['section'] ?? 'welcome';
$menu = [
  'users' => 'Users',
  'dogs' => 'Dogs',
  'adoptions' => 'Adoptions',
  'reviews' => 'Reviews',
  'news' => 'News',
  'events' => 'Events',
  'products' => 'Products',
  'team' => 'Team',
  'stories' => 'Success Stories',
];
$pageTitle = $section === 'welcome' ? 'Dashboard' : ($menu[$section] ?? 'Dashboard');
?>

<div class="admin-shell">
<aside class="admin-aside">
    <div class="admin-aside-card">
        <div class="admin-avatar"><img src="profile.png" alt="Admin"></div>
        <div class="admin-meta"><p class="admin-name">Admin</p><p class="admin-role">Administrator</p></div>
    </div>
    <nav class="admin-menu">
        <?php foreach($menu as $key => $label): ?>
        <a class="admin-link <?php echo ($section === $key) ? 'active' : ''; ?>" href="?section=<?php echo htmlspecialchars($key); ?>">
            <?php echo htmlspecialchars($label); ?>
        </a>
        <?php endforeach; ?>
        <div class="admin-sep"></div>
        <a class="admin-link logout" href="logout.php">Log Out</a>
    </nav>
</aside>

<main class="admin-main">
<div class="admin-main-head">
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <p class="admin-sub"><?php echo ($section === 'welcome') ? 'Welcome Admin!' : 'Manage ' . htmlspecialchars($pageTitle) . ' here.'; ?></p>
</div>

<div class="admin-card">

<?php if($section === 'welcome'): ?>
    <div class="admin-welcome"><h2>Welcome Admin!</h2><p>Select a section from the left menu to manage your data.</p></div>

<?php elseif($section === 'users'): ?>
    <div class="admin-users">
        <?php if($message) echo "<p class='success'>" . htmlspecialchars($message) . "</p>"; ?>
        <?php if($error) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

        <form method="POST" action="?section=users">
            <input type="hidden" name="id" value="<?php echo $editUser['id'] ?? ''; ?>">
            <input type="hidden" name="action" value="<?php echo $editUser ? 'update' : 'create'; ?>">

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $editUser['name'] ?? ''; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $editUser['email'] ?? ''; ?>" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="<?php echo $editUser ? 'Leave blank to keep' : ''; ?>">

            <label>Role:</label>
            <select name="role">
                <option value="user" <?php if(($editUser['role'] ?? '') === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if(($editUser['role'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
            </select>

            <button type="submit"><?php echo $editUser ? 'Update' : 'Create'; ?></button>
            <?php if($editUser): ?><a href="?section=users">Cancel</a><?php endif; ?>
        </form>

        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="?section=users&action=edit&id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="?section=users&action=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
    </div>

<?php elseif($section === 'dogs'): ?>
    <!-- DOGS SECTION -->
    <!-- TODO: Add Dogs management form & table -->

<?php elseif($section === 'adoptions'): ?>
    <!-- ADOPTIONS SECTION -->
    <!-- TODO: Add Adoptions management form & table -->

<?php elseif($section === 'reviews'): ?>
    <div class="admin-reviews">
        <?php if($reviewMessage) echo "<p class='success'>" . htmlspecialchars($reviewMessage) . "</p>"; ?>
        <?php if($reviewError) echo "<p class='error'>" . htmlspecialchars($reviewError) . "</p>"; ?>

        <form method="POST" action="?section=reviews">
            <input type="hidden" name="id" value="<?php echo $editReview['id'] ?? ''; ?>">
            <input type="hidden" name="action" value="<?php echo $editReview ? 'update' : 'add'; ?>">

            <label>Product ID:</label>
            <input type="number" name="product_id" value="<?php echo $editReview['product_id'] ?? ''; ?>" required>

            <label>User ID:</label>
            <input type="number" name="user_id" value="<?php echo $editReview['user_id'] ?? ''; ?>" required>

            <label>Rating (1-5):</label>
            <input type="number" name="rating" min="1" max="5" value="<?php echo $editReview['rating'] ?? ''; ?>" required>

            <label>Comment:</label>
            <input type="text" name="comment" value="<?php echo $editReview['comment'] ?? ''; ?>" required>

            <button type="submit"><?php echo $editReview ? 'Update' : 'Add'; ?></button>
            <?php if($editReview): ?><a href="?section=reviews">Cancel</a><?php endif; ?>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Created At</th><th>Updated At</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reviews as $review): ?>
                <tr>
                    <td><?php echo $review['id']; ?></td>
                    <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td><?php echo $review['rating']; ?></td>
                    <td><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td><?php echo $review['created_at']; ?></td>
                    <td><?php echo $review['updated_at'] ?? '-'; ?></td>
                    <td>
                        <a href="?section=reviews&action=edit&id=<?php echo $review['id']; ?>">Edit</a>
                        <a href="?section=reviews&action=delete&id=<?php echo $review['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
    </div>

<?php elseif($section === 'news'): ?>
    <!-- NEWS SECTION -->
    <!-- TODO: Add News management form & table -->

<?php elseif($section === 'events'): ?>
    <!-- EVENTS SECTION -->
    <!-- TODO: Add Events management form & table -->

<?php elseif($section === 'products'): ?>
    <!-- PRODUCTS TABLE & FORM -->
    <div class="admin-products">
    <?php if($message) echo "<p class='success'>" . htmlspecialchars($message) . "</p>"; ?>
    <?php if($error) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST" action="?section=products" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $editProduct['id'] ?? ''; ?>">
        <input type="hidden" name="action" value="<?php echo $editProduct ? 'update' : 'create'; ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo $editProduct['title'] ?? ''; ?>" required>
        <label>Description:</label>
        <textarea name="description" rows="3" required><?php echo $editProduct['description'] ?? ''; ?></textarea>
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $editProduct['price'] ?? ''; ?>" required>
        <label>Category:</label>
        <input type="text" name="category" value="<?php echo $editProduct['category'] ?? ''; ?>" required>
        <label>Image:</label>
        <input type="file" name="image" <?php echo $editProduct ? '' : 'required'; ?>>
        <?php if($editProduct && !empty($editProduct['image'])): ?>
            <img src="<?php echo htmlspecialchars($editProduct['image']); ?>" alt="Product Image" style="height:60px;margin-top:5px;">
        <?php endif; ?>
        <button type="submit"><?php echo $editProduct ? 'Update' : 'Add'; ?></button>
        <?php if($editProduct): ?><a href="?section=products">Cancel</a><?php endif; ?>
    </form>

    <table>
        <thead>
            <tr><th>ID</th><th>Title</th><th>Description</th><th>Price</th><th>Category</th><th>Image</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['title']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
                <td><?php if(!empty($product['image'])): ?><img src="<?php echo htmlspecialchars($product['image']); ?>" style="height:40px;"><?php endif; ?></td>
                <td>
                    <a href="?section=products&action=edit&id=<?php echo $product['id']; ?>">Edit</a>
                    <a href="?section=products&action=delete&id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

<?php elseif($section === 'team'): ?>
    <!-- TEAM TABLE & FORM -->
    <div class="admin-team">
    <form method="POST" action="?section=team" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $editMember['id'] ?? ''; ?>">
        <input type="hidden" name="action" value="<?php echo $editMember ? 'update' : 'create'; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $editMember['name'] ?? ''; ?>" required>
        <label>Role:</label>
        <input type="text" name="role" value="<?php echo $editMember['role'] ?? ''; ?>" required>
        <label>Image:</label>
        <input type="file" name="image" <?php echo $editMember ? '' : 'required'; ?>>
        <?php if($editMember && !empty($editMember['image'])): ?><img src="<?php echo htmlspecialchars($editMember['image']); ?>" style="height:60px;margin-top:5px;"><?php endif; ?>
        <button type="submit"><?php echo $editMember ? 'Update' : 'Add'; ?></button>
        <?php if($editMember): ?><a href="?section=team">Cancel</a><?php endif; ?>
    </form>

    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($teamMembers as $member): ?>
            <tr>
                <td><?php echo $member['id']; ?></td>
                <td><?php echo htmlspecialchars($member['name']); ?></td>
                <td><?php echo htmlspecialchars($member['role']); ?></td>
                <td><?php if(!empty($member['image'])): ?><img src="<?php echo htmlspecialchars($member['image']); ?>" style="height:40px;"><?php endif; ?></td>
                <td>
                    <a href="?section=team&action=edit&id=<?php echo $member['id']; ?>">Edit</a>
                    <a href="?section=team&action=delete&id=<?php echo $member['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

<?php elseif($section === 'stories'): ?>
    <!-- SUCCESS STORIES SECTION -->
    <!-- TODO: Add Success Stories management form & table -->
<?php endif; ?>

</div>
</main>
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
            <p> &copy; 2025 PawCare. All rights reserved. Made with love for pets.</p>
        </div>
    </div>
</footer>

<script src="cart.js"></script>
</body>
</html>
