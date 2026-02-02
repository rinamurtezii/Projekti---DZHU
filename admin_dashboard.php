<?php
require_once "admin_guard.php"; 
require_once "DataBase.php";
require_once "Users.php";

$db = new Database();
$pdo = $db->startConnection();
$userObj = new User($pdo);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
$editUser = null;
if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    $editUser = $userObj->find($id);
}

$users = $userObj->getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCare - Home</title>
    <link rel="stylesheet" href="admin.css">
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

    $pageTitle = $section === 'welcome'
      ? 'Dashboard'
      : ($menu[$section] ?? 'Dashboard');
  ?>

  <div class="admin-shell">
    <aside class="admin-aside">
      <div class="admin-aside-card">
        <div class="admin-avatar">
          <img src="profile.png" alt="Admin">
        </div>
        <div class="admin-meta">
          <p class="admin-name">Admin</p>
          <p class="admin-role">Administrator</p>
        </div>
      </div>

      <nav class="admin-menu">
        <?php foreach($menu as $key => $label): ?>
          <a
            class="admin-link <?php echo ($section === $key) ? 'active' : ''; ?>"
            href="?section=<?php echo htmlspecialchars($key); ?>"
          >
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
        <p class="admin-sub">
          <?php echo ($section === 'welcome') ? 'Welcome Admin!' : 'Manage ' . htmlspecialchars($pageTitle) . ' here.'; ?>
        </p>
      </div>

      <div class="admin-card">
        <?php if($section === 'welcome'): ?>
          <div class="admin-welcome">
            <h2>Welcome Admin!</h2>
            <p>Select a section from the left menu to manage your data.</p>
          </div>

        <?php elseif($section === 'users'): ?>
        <div class="admin-users">
        <?php if(!empty($message)): ?>
                <p class="success"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <?php if(!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
          
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
                <?php if($editUser): ?>
                    <a href="?section=users">Cancel</a>
                <?php endif; ?>
            </form>

             <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
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

        <?php else: ?>
          <div class="admin-placeholder">
            <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
            <p>CRUD for <?php echo htmlspecialchars($pageTitle); ?> will appear here.</p>
          </div>

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

?>
