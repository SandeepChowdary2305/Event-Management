<?php
session_start();

$admin_user = "admin";
$admin_pass = "admin123"; // change as needed

$user = $_POST['username'];
$pass = $_POST['password'];

if ($user === $admin_user && $pass === $admin_pass) {
    $_SESSION['username'] = $user;
    header("Location: admin.php");
    exit();
} else {
    $_SESSION['username'] = $user; // simple user
    header("Location: index.php");
    exit();
}
?>
