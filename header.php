<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style/style.css">
    <title>Event Management</title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['username'])): ?>
                <?php if ($_SESSION['username'] !== 'admin'): ?>
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
