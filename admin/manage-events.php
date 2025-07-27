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
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            die("Upload error code: " . $_FILES['image']['error']);
        }

        // Change this absolute path to your actual uploads directory on your server
        $targetDir = "/opt/lampp/htdocs/Event-Management/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES['image']['name']);
        $uniqueName = time() . "_" . $fileName;
        $targetFile = $targetDir . $uniqueName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $tmpName = $_FILES['image']['tmp_name'];
            echo "Tmp file exists? " . (file_exists($tmpName) ? "Yes" : "No") . "<br>";
            die("Failed to move uploaded file.");
        }

        // Save relative path for HTML <img> src
        $imagePath = "uploads/" . $uniqueName;
    }

    $stmt = $conn->prepare("INSERT INTO event_types (name, description, image) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $name, $description, $imagePath);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    header("Location: manage-events.php");
    exit();
}

$events = mysqli_query($conn, "SELECT * FROM event_types");
if (!$events) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Events | Admin - Celebria</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      <a href="manage-events.php" style="background:#fff; color:#006EA8; font-weight:bold;">Events</a>
      <a href="manage-venues.php">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="event-main-content p-4">
    <h2>Manage Events</h2>

    <form action="" method="POST" enctype="multipart/form-data" class="event-form mb-4">
      <input type="text" name="name" placeholder="Event Name" required class="form-control mb-2" />
      <textarea name="description" placeholder="Event Description" class="form-control mb-2"></textarea>
      <input type="file" name="image" accept="image/*" class="form-control mb-3" />
      <button type="submit" name="add" class="btn btn-primary">Add Event</button>
    </form>

    <div class="event-table-wrapper">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th style="width: 140px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($event = mysqli_fetch_assoc($events)) : ?>
            <tr>
              <td><?= htmlspecialchars($event['id']) ?></td>
              <td><?= htmlspecialchars($event['name']) ?></td>
              <td><?= htmlspecialchars($event['description']) ?></td>
              <td>
                <a href="edit-event.php?id=<?= htmlspecialchars($event['id']) ?>" class="btn btn-sm btn-warning me-2 m-1">
                  <i class="bi bi-pencil-square m-1"></i> Edit
                </a>
                <a href="delete-event.php?id=<?= htmlspecialchars($event['id']) ?>" 
                   class="btn btn-sm btn-danger m-1" 
                   onclick="return confirm('Are you sure you want to delete this event?');">
                  <i class="bi bi-trash m-1"></i> Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Bootstrap JS (optional, for some components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
