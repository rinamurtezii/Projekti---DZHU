<?php
session_start();
require_once "DataBase.php";
require_once "Users.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new DataBase();
$pdo = $db->startConnection();

$userObj = new User($pdo);
$userId = (int)$_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name !== '' && $email !== '') {
        $userObj->updateNameEmail($userId, $name, $email);
    }

    header("Location: account.php?editProfile=1");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {

    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';

    if ($current !== '' && $new !== '') {

        $user = $userObj->getUserById($userId);

        if ($user && password_verify($current, $user['password'])) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $userObj->updatePassword($userId, $hashed);
        }
    }

    header("Location: account.php?editProfile=1");
    exit;
}

header("Location: account.php");
exit;
