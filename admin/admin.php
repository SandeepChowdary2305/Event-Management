<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<h1>Admin Dashboard</h1>
<p>Welcome, Admin!</p>
<?php include 'footer.php'; ?>
