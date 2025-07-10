<?php
include("db.php");

if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
