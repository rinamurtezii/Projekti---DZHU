<?php
$section = $_GET['section'] ?? 'welcome';
$action  = $_GET['action'] ?? $_POST['action'] ?? '';

require_once "admin_guard.php"; 
require_once "DataBase.php";
require_once "Users.php";
require_once "DogModel.php";
require_once "AdoptionRequest.php";
require_once 'Review.php';
require_once 'NewsletterModel.php';
require_once 'Product.php';
require_once 'Team.php';

function e($v){
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$db = new Database();
$pdo = $db->startConnection();
$userObj = new User($pdo);
$dogModel = new DogModel($pdo);
$adoptionObj = new AdoptionRequest($pdo);
$reviewObj = new Review();
$productObj = new Product();
$teamObj = new Team();
$newsletterObj = new NewsletterModel($pdo);
$news = $newsletterObj->getNews(1, 100);
$events = $newsletterObj->getEvents(100);

$message = '';
$error = '';
$reviewMessage = '';
$reviewError = '';
$editAdoption = null;  
$adoptions = [];
$editDog = null;
$editUser = null;
$editReview = null;
$editProduct = null;
$editMember = null;
$editItem = null; 


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
$action = $_GET['action'] ?? $_POST['action'] ?? '';
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($userObj->deleteUser($id)) {
        $message = "User deleted successfully";
    } else {
        $error = "You cannot delete this user";
    }
}
if ($section === 'dogs') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'age' => $_POST['age'],
            'energy' => $_POST['energy'],
            'size' => $_POST['size'],
            'status' => $_POST['status']
        ];

        if (!empty($_FILES['image']['name'])) {
            $img = 'uploads/' . time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $img);
            $data['image'] = $img;
        }

        if ($action === 'create') {
            $dogModel->create($data);
            $message = "Dog added successfully";
        }

        if ($action === 'update' && $id > 0) {
            $dogModel->update($id, $data);
            $message = "Dog updated successfully";
        }
    }

    if ($action === 'delete') {
        $dogModel->delete((int)$_GET['id']);
        $message = "Dog deleted successfully";
    }

    if ($action === 'edit') {
        $editDog = $dogModel->getById((int)$_GET['id']);
    }
}

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

if($section === 'adoptions') {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
        $id = (int)($_GET['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        if($adoptionObj->updateStatus($id, $status)) {
            $message = "Status updated successfully";
        } else {
            $error = "Failed to update status";
        }
    }

    if($action === 'delete') {
        $id = (int)($_GET['id'] ?? 0);
        if($adoptionObj->delete($id)) {
            $message = "Adoption request deleted successfully";
        } else {
            $error = "Failed to delete request";
        }
    }

    if($action === 'edit') {
        $id = (int)($_GET['id'] ?? 0);
        $editAdoption = $adoptionObj->getById($id);
    }
}

$products = $productObj->getAllProducts();
if(isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit'){
    $editProduct = $productObj->find((int)$_GET['id']);
}

$teamMembers = $teamObj->getAll();
if(isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit'){
    $editMember = $teamObj->find((int)$_GET['id']);
}

$reviews = $reviewObj->getAllReviews();
$users = $userObj->getAllUsers();
$dogs = $dogModel->getDogs() ?? [];
$adoptions = $adoptionObj->getAll();
$news = $pdo ? $pdo->query("SELECT * FROM news ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC) : [];
$events = $pdo ? $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PawCare - Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
<style>

*{ box-sizing:border-box; }
body{
  margin:0;
  background:#f9f9f9;
  color:#333;
  font-family:'Poppins',sans-serif;
}
h1,h2,h3,h4{
  font-family:'Fredoka',sans-serif;
  margin:0 0 10px 0;
  color:#222;
}
a{ color:inherit; text-decoration:none; }

.container{
  max-width:1200px;
  margin:30px auto;
  padding:0 18px;
}

.admin-shell{
  display:grid;
  grid-template-columns:280px 1fr;
  gap:22px;
  align-items:start;
}

.admin-aside{
  position:sticky;
  top:20px;
}

.admin-aside-card{
  background:#fff;
  border-radius:16px;
  padding:18px;
  box-shadow:0 10px 25px rgba(0,0,0,0.06);
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:14px;
}

.admin-avatar{
  width:62px;
  height:62px;
  border-radius:50%;
  background:#f3f3f3;
  overflow:hidden;
  flex:0 0 auto;
  display:grid;
  place-items:center;
}
.admin-avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.admin-meta .admin-name{
  margin:0;
  font-size:18px;
  font-weight:600;
  font-family:'Fredoka',sans-serif;
}
.admin-meta .admin-role{
  margin:2px 0 0;
  font-size:13px;
  opacity:.65;
}

.admin-menu{
  background:#fff;
  border-radius:16px;
  padding:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.06);
}

.admin-link{
  display:flex;
  align-items:center;
  gap:10px;
  padding:12px 12px;
  border-radius:12px;
  color:#222;
  font-weight:500;
  transition:.15s ease;
}
.admin-link:hover{ background:#f6f6f6; }

.admin-link.active{
  background:#fff3ea;
  color:#c85a12;
  outline:2px solid rgba(255,138,61,0.25);
}

.admin-sep{
  height:1px;
  background:rgba(0,0,0,0.06);
  margin:10px 6px;
}

.admin-link.logout{
  color:#b00020;
}
.admin-link.logout:hover{
  background:rgba(176,0,32,0.08);
}

.admin-main-head{
  background:#fff;
  border-radius:16px;
  padding:18px 20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.06);
  margin-bottom:14px;
}
.admin-main-head h1{
  margin:0;
  font-size:22px;
}
.admin-sub{
  margin:6px 0 0;
  opacity:.7;
  font-size:14px;
}

.admin-card{
  background:#fff;
  border-radius:16px;
  padding:18px 20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.06);
  min-height:420px;
}

table{
  width:100%;
  border-collapse:collapse;
  font-family:'Poppins',sans-serif;
  margin-top:10px;
}
th,td{
  text-align:left;
  padding:12px 10px;
  border-bottom:1px solid #eee;
}
th{
  font-family:'Fredoka',sans-serif;
  font-weight:600;
  font-size:15px;
  background:#fff3ea;
  color:#c85a12;
}
tr:hover{ background:#fff7f0; }

label{
  font-family:'Fredoka',sans-serif;
  font-weight:500;
  display:block;
  margin-bottom:5px;
}
input,select,textarea{
  width:100%;
  padding:10px 12px;
  border-radius:8px;
  border:1px solid #ddd;
  font-family:'Poppins',sans-serif;
  font-size:14px;
}
button{
  padding:10px 18px;
  background:#ff8a3d;
  color:#fff;
  font-family:'Fredoka',sans-serif;
  font-weight:600;
  border:none;
  border-radius:12px;
  cursor:pointer;
  transition:.2s ease;
  margin-top:10px;
}
button:hover{ background:#e6752e; }

.success{
  background:#daf5d7;
  color:#3d7d3d;
  padding:10px 14px;
  border-radius:10px;
  margin-bottom:15px;
}
.error{
  background:#fddede;
  color:#b00020;
  padding:10px 14px;
  border-radius:10px;
  margin-bottom:15px;
}
@media (max-width:980px){
  .admin-shell{ grid-template-columns:1fr; }
  .admin-aside{ position:static; }
}
</style>

</head>
<body>

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
  'team' => 'Team'
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
<div class="admin-dogs">

    <?php if($message): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="?section=dogs" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editDog['id'] ?? '' ?>">
        <input type="hidden" name="action" value="<?= $editDog ? 'update' : 'create' ?>">

        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($editDog['name'] ?? '') ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($editDog['description'] ?? '') ?></textarea>

        <label>Age:</label>
        <input type="number" name="age" value="<?= $editDog['age'] ?? '' ?>" required>

        <label>Energy:</label>
        <input type="text" name="energy" value="<?= htmlspecialchars($editDog['energy'] ?? '') ?>">

        <label>Size:</label>
        <input type="text" name="size" value="<?= htmlspecialchars($editDog['size'] ?? '') ?>">

        <label>Status:</label>
        <select name="status">
            <option value="available" <?= (($editDog['status'] ?? '') === 'available') ? 'selected' : '' ?>>Available</option>
            <option value="adopted" <?= (($editDog['status'] ?? '') === 'adopted') ? 'selected' : '' ?>>Adopted</option>
        </select>

        <label>Image:</label>
        <input type="file" name="image" <?= $editDog ? '' : 'required' ?>>
        <?php if(!empty($editDog['image'])): ?>
            <img src="<?= htmlspecialchars($editDog['image']) ?>" style="height:60px;margin-top:5px;">
        <?php endif; ?>

        <button type="submit"><?= $editDog ? 'Update' : 'Add' ?></button>
        <?php if($editDog): ?>
            <a href="?section=dogs">Cancel</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Age</th><th>Energy</th>
                <th>Size</th><th>Status</th><th>Image</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dogs as $dog): ?>
            <tr>
                <td><?= $dog['id'] ?></td>
                <td><?= htmlspecialchars($dog['name']) ?></td>
                <td><?= $dog['age'] ?></td>
                <td><?= htmlspecialchars($dog['energy']) ?></td>
                <td><?= htmlspecialchars($dog['size']) ?></td>
                <td><?= htmlspecialchars($dog['status']) ?></td>
                <td>
                    <?php if(!empty($dog['image'])): ?>
                        <img src="<?= htmlspecialchars($dog['image']) ?>" style="height:150px;">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?section=dogs&action=edit&id=<?= $dog['id'] ?>">Edit</a>
                    <a href="?section=dogs&action=delete&id=<?= $dog['id'] ?>"
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php elseif($section === 'adoptions'): ?>
    <div class="admin-adoptions">

    <h2>Adoption Requests</h2>

    <?php if($editAdoption): ?>
    <form method="POST" action="?section=adoptions&action=update&id=<?= $editAdoption['id'] ?>">
        <label>Full Name</label>
        <input type="text" value="<?= e($editAdoption['full_name']) ?>" disabled>

        <label>Dog ID</label>
        <input type="text" value="<?= e($editAdoption['dog_id']) ?>" disabled>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $editAdoption['status']==='pending'?'selected':'' ?>>Pending</option>
            <option value="approved" <?= $editAdoption['status']==='approved'?'selected':'' ?>>Approved</option>
            <option value="rejected" <?= $editAdoption['status']==='rejected'?'selected':'' ?>>Rejected</option>
            <option value="cancelled" <?= $editAdoption['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
        </select>

        <button type="submit">Update Status</button>
        <a href="?section=adoptions">Cancel</a>
    </form>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Dog ID</th><th>User ID</th><th>Full Name</th><th>Phone</th><th>Address</th><th>Email</th><th>Reason</th><th>Status</th><th>Created At</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($adoptions as $ad): ?>
            <tr>
                <td><?= $ad['id'] ?></td>
                <td><?= e($ad['dog_id']) ?></td>
                <td><?= e($ad['user_id']) ?></td>
                <td><?= e($ad['full_name']) ?></td>
                <td><?= e($ad['phone']) ?></td>
                <td><?= e($ad['address']) ?></td>
                <td><?= e($ad['email']) ?></td>
                <td><?= e($ad['reason']) ?></td>
                <td><?= ucfirst($ad['status']) ?></td>
                <td><?= $ad['created_at'] ?></td>
                <td>
                    <a href="?section=adoptions&action=edit&id=<?= $ad['id'] ?>">Edit</a>
                    <a href="?section=adoptions&action=delete&id=<?= $ad['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

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

<?php elseif($section === 'products'): ?>
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
    <div class="admin-team">
    <form method="POST" action="?section=team" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $editMember['id'] ?? ''; ?>">
        <input type="hidden" name="action" value="<?php echo $editMember ? 'update' : 'create'; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $editMember['name'] ?? ''; ?>" required>
        <label>Role:</label>
        <input type="text" name="role" value="<?php echo $editMember['role'] ?? ''; ?>" required>
        <button type="submit"><?php echo $editMember ? 'Update' : 'Add'; ?></button>
        <?php if($editMember): ?>
            <a href="?section=team" style="margin-left:10px;">Cancel</a>
        <?php endif; ?>
    </form>

    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Role</th><th><th>Actions</th></tr></thead>
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
    <?php elseif($section === 'news' || $section === 'events'): ?>
    <?php
        if($section === 'news') { 
            $items = $news ?? []; 
            $titleLabel = 'Title'; 
            $dateField = 'created_at';
        }
        elseif($section === 'events') { 
            $items = $events ?? []; 
            $titleLabel = 'Event Name'; 
            $dateField = 'created_at';
        }
    ?>

    <div class="admin-<?php echo $section; ?>">
        <?php if($message) echo "<p class='success'>" . htmlspecialchars($message) . "</p>"; ?>
        <?php if($error) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

        <form method="POST" action="?section=<?php echo $section; ?>">
            <input type="hidden" name="id" value="<?php echo $editItem['id'] ?? ''; ?>">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">

            <label><?php echo $titleLabel; ?>:</label>
            <input type="text" name="title" value="<?php echo $editItem['title'] ?? ''; ?>" required>

            <label>Content / Story:</label>
            <textarea name="content" required><?php echo $editItem['content'] ?? ''; ?></textarea>

            <?php if($section === 'events'): ?>
                <label>Meta / Info:</label>
                <input type="text" name="meta" value="<?php echo $editItem['meta'] ?? ''; ?>">
            <?php endif; ?>

            <label>Date:</label>
            <input type="date" name="date" value="<?php echo $editItem[$dateField] ?? ''; ?>" required>

            <button type="submit"><?php echo $editItem ? 'Update' : 'Create'; ?></button>
            <?php if($editItem): ?><a href="?section=<?php echo $section; ?>">Cancel</a><?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php echo $titleLabel; ?></th>
                    <?php if($section === 'events') echo '<th>Meta</th>'; ?>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>

                    <?php if($section === 'events'): ?>
                        <td><?php echo htmlspecialchars($item['meta']); ?></td>
                    <?php endif; ?>

                    <td><?php echo htmlspecialchars($item[$dateField]); ?></td>
                    <td>
                        <a href="?section=<?php echo $section; ?>&action=edit&id=<?php echo $item['id']; ?>">Edit</a>
                        <a href="?section=<?php echo $section; ?>&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
</div>
</main>
</div>
</body>
</html>
