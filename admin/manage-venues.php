<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $venue_id = intval($_GET['delete']);
    $conn->query("DELETE FROM venues WHERE id = $venue_id");
    header("Location: manage-venues.php");
    exit();
}

$result = $conn->query("SELECT * FROM venues ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Venues | Admin - Celebria</title>
  <link rel="stylesheet" href="style/admin.css">
</head>
<body>

<div class="admin-wrapper">
  <?php include 'sidebar.php'; ?>

  <main class="admin-main">
    <h1>Manage Venues</h1>
    <a href="add_venue.php" class="btn-add">+ Add Venue</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Venue Name</th>
          <th>Location</th>
          <th>Capacity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($venue = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $venue['id'] ?></td>
            <td><?= htmlspecialchars($venue['name']) ?></td>
            <td><?= htmlspecialchars($venue['location']) ?></td>
            <td><?= $venue['capacity'] ?></td>
            <td>
              <a href="edit_venue.php?id=<?= $venue['id'] ?>">Edit</a> |
              <a href="manage-venues.php?delete=<?= $venue['id'] ?>" onclick="return confirm('Delete this venue?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>

</body>
</html>
