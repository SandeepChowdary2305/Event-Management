<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="style/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="dashboard">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
  <p>You are successfully logged in as a user.</p>

  <!-- Add more user features below -->
  <ul>
    <li><a href="book_event.php">Book New Event</a></li>
    <li><a href="my_bookings.php">View My Bookings</a></li>
    <li><a href="edit_profile.php">Edit Profile</a></li>
  </ul>
</div>

</body>
</html>
