<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<?php include 'header.php'; ?>
<h1>Admin Dashboard</h1>
<p>Welcome, Admin!</p>
<?php include 'footer.php'; ?>
