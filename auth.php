<?php
session_start();

// Define admin credentials
$admin_user = "admin";
$admin_pass = "admin123";

// Get user input safely
$user = isset($_POST['username']) ? trim($_POST['username']) : '';
$pass = isset($_POST['password']) ? trim($_POST['password']) : '';

// Basic check
if ($user === $admin_user && $pass === $admin_pass) {
    $_SESSION['role'] = 'admin';
    $_SESSION['username'] = $user;
    header("Location: admin/admin.php");
    exit();
} else {
    $_SESSION['role'] = 'user';
    $_SESSION['username'] = $user;
    header("Location: user.php"); // index.php reloads form — better to redirect to user page
    exit();
}
?>
