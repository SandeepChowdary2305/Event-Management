<?php
include '../db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO event_types (name, description, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $imagePath);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-event-types.php");
    exit;
}

$events = mysqli_query($conn, "SELECT * FROM event_types");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Event Types | Admin - Celebria</title>
  <link rel="stylesheet" href="style/admin.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>

<div class="event-admin-wrapper">
  <!-- Sidebar -->
  <aside class="event-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="manage-event-types.php" style="background:#fff; color:#006EA8; font-weight:bold;">Events</a>
      <a href="manage-venues.php">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="event-main-content">
    <h2>Manage Event Types</h2>

    <form action="" method="POST" enctype="multipart/form-data" class="event-form">
      <input type="text" name="name" placeholder="Event Name" required />
      <textarea name="description" placeholder="Event Description"></textarea>
      <input type="file" name="image" accept="image/*" />
      <button type="submit" name="add">Add Event</button>
    </form>

    <div class="event-table-wrapper">
      <table class="event-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($event = mysqli_fetch_assoc($events)) : ?>
            <tr>
              <td><?= $event['id'] ?></td>
              <td>
                <?php if (!empty($event['image'])): ?>
                  <img src="<?= $event['image'] ?>" alt="Event Image" width="80" height="60" />
                <?php else: ?>
                  <span>No Image</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($event['name']) ?></td>
              <td><?= htmlspecialchars($event['description']) ?></td>
              <td>
                <a href="edit-event.php?id=<?= $event['id'] ?>">Edit</a> |
                <a href="delete-event.php?id=<?= $event['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

</body>
</html>
