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
  <style>
    .ev-container {
      width: 100%;
      margin: 0 auto;
      padding: 20px;
    }

    .ev-heading {
      text-align: center;
      margin-bottom: 30px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #006EA8;
    }

    .ev-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .ev-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: center;
      transition: transform 0.2s ease-in-out;
    }

    .ev-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .event-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .ev-title {
      font-size: 1.4rem;
      margin-bottom: 10px;
      color: #003a63;
    }

    .ev-description {
      font-size: 1rem;
      color: #555;
      line-height: 1.4;
    }

    .book-button {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #006EA8;
      color: white;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease;
    }

    .book-button:hover {
      background-color: #004f7c;
    }
  </style>
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

          $imagePath = (!empty($event['image']) && file_exists($event['image']))
                        ? htmlspecialchars($event['image'])
                        : 'images/placeholder.png';

          echo "<img src='$imagePath' alt='" . htmlspecialchars($event['name']) . "' class='event-image' />";
          echo "<h3 class='ev-title'>" . htmlspecialchars($event['name']) . "</h3>";
          echo "<p class='ev-description'>" . htmlspecialchars($event['description']) . "</p>";

          // Book button as link using GET
          echo "<a href='book.php?event_id=" . urlencode($event['id']) . "' class='book-button'>Book</a>";

          echo "</div>";
      }
      ?>
    </div>
  </div>

</body>
</html>
