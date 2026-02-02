<?php
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location: login.php?return=" . urlencode($_SERVER['REQUEST_URI']));
  exit;
}

if(($_SESSION['user_role'] ?? 'user') !== 'admin'){
  header("Location: indexi.php");
  exit;
}
