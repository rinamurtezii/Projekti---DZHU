<?php
require 'DataBase.php';

$db = new DataBase();

try {
    $conn = $db->startConnection();
    echo "✅ Database connection SUCCESS!";
} catch (Exception $e) {
    echo "❌ Database connection FAILED: " . $e->getMessage();
}
?>