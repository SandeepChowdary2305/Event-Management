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
</div><title>User Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f0faff, #ffffff);
      color: #333;
    }

    .dashboard {
      max-width: 700px;
      margin: 80px auto;
      padding: 40px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 110, 168, 0.1);
      animation: fadeIn 0.6s ease-in-out;
      text-align: center;
    }

    .dashboard h2 {
      color: #006EA8;
      font-size: 28px;
      margin-bottom: 10px;
    }

    .dashboard p {
      font-size: 16px;
      color: #555;
      margin-bottom: 30px;
    }

    .dashboard ul {
      list-style: none;
      padding-left: 0;
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .dashboard li {
      margin-bottom: 15px;
    }

    .dashboard a {
      text-decoration: none;
      color: #fff;
      background-color: #006EA8;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 500;
      transition: background-color 0.3s ease, transform 0.2s ease;
      display: inline-block;
    }

    .dashboard a:hover {
      background-color: #004e7c;
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

 
</body>
</html>
