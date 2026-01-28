<?php
require 'DataBase.php';  

echo "Test connection: ";

if ($conn) {
    echo "Success!";
} else {
    echo "Failed: " . $conn->connect_error;
}
?>