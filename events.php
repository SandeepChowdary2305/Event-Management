<?php
session_start();
include 'db.php'; // Replace with your actual DB connection file

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Events</title>
  <link rel="stylesheet" href="style/style.css"> 
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <div class="ev-container">
    <h2 class="ev-heading">Available Event Types</h2>
    <div class="ev-grid">
      <?php
      $events = mysqli_query($conn, "SELECT * FROM event_types");
      while ($event = mysqli_fetch_assoc($events)) {
          echo "<div class='ev-card'>";
          echo "<h3 class='ev-title'>" . htmlspecialchars($event['name']) . "</h3>";
          echo "<p class='ev-description'>" . htmlspecialchars($event['description']) . "</p>";
          echo "</div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
