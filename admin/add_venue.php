<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $capacity = intval($_POST['capacity']);

    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO venues (name, location, capacity) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $location, $capacity);
        $stmt->execute();
        header("Location: manage-venues.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Venue</title>
  <link rel="stylesheet" href="style/admin.css">
</head>
<body>

<div class="admin-wrapper">
  <?php include 'sidebar.php'; ?>

  <main class="admin-main">
    <h2>Add New Venue</h2>
    <form method="POST">
      <label>Venue Name:</label>
      <input type="text" name="name" required>

      <label>Location:</label>
      <input type="text" name="location">

      <label>Capacity:</label>
      <input type="number" name="capacity" min="0">

      <button type="submit">Add Venue</button>
    </form>
  </main>
</div>

</body>
</html>
