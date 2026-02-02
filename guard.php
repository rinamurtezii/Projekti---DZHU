<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login(string $redirectTo = 'login.php'): void {
  if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0) {
    $_SESSION['flash_error'] = "You must be signed in to submit an adoption application.";
    $return = $_SERVER['REQUEST_URI'] ?? 'adopt.php';
    header("Location: {$redirectTo}?return=" . urlencode($return));
    exit;
  }
}

function is_logged_in(): bool {
  return isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;
}

?>