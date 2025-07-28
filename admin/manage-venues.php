<?php
include '../db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Add Venue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $capacity = intval($_POST['capacity']);
    $description = $_POST['description'];

    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            die("Upload error code: " . $_FILES['image']['error']);
        }

        $targetDir = "/opt/lampp/htdocs/Event-Management/uploads/venues/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES['image']['name']);
        $uniqueName = time() . "_" . $fileName;
        $targetFile = $targetDir . $uniqueName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            die("Failed to move uploaded file.");
        }

        $imagePath = "uploads/venues/" . $uniqueName;
    }

    $stmt = $conn->prepare("INSERT INTO venues (name, location, capacity, description, image) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssiss", $name, $location, $capacity, $description, $imagePath);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    header("Location: manage-venues.php");
    exit();
}

// Fetch all venues
$venues = mysqli_query($conn, "SELECT * FROM venues ORDER BY id DESC");
if (!$venues) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Venues | Admin - Celebria</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style/admin.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <style>
    /* Fixed widths for table columns */
    .venue-table-wrapper table th,
    .venue-table-wrapper table td {
      vertical-align: middle;
      white-space: nowrap;
    }

    .venue-table-wrapper table th.id-col,
    .venue-table-wrapper table td.id-col {
      width: 50px;
      text-align: center;
    }

    .venue-table-wrapper table th.name-col,
    .venue-table-wrapper table td.name-col {
      width: 20%;
    }

    .venue-table-wrapper table th.location-col,
    .venue-table-wrapper table td.location-col {
      width: 20%;
    }

    .venue-table-wrapper table th.capacity-col,
    .venue-table-wrapper table td.capacity-col {
      width: 80px;
      text-align: center;
    }

    .venue-table-wrapper table th.description-col,
    .venue-table-wrapper table td.description-col {
      width: 25%;
      white-space: normal;
    }

    .venue-table-wrapper table th.image-col,
    .venue-table-wrapper table td.image-col {
      width: 100px;
      text-align: center;
    }

    .venue-table-wrapper table th.actions-col,
    .venue-table-wrapper table td.actions-col {
      width: 140px;
      white-space: nowrap;
    }
    input{
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
  </style>
</head>
<body>

<div class="event-admin-wrapper">
  <!-- Sidebar -->
  <aside class="event-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="manage-events.php">Events</a>
      <a href="manage-venues.php" style="background:#fff; color:#006EA8; font-weight:bold;">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="contact_message.php">Contact</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="event-main-content p-4">
    <h2>Manage Venues</h2>

    <!-- Add Venue Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="event-form mb-4">
      <input type="text" name="name" placeholder="Venue Name" required class=" mb-2 " />
      <input type="text" name="location" placeholder="Location" required class=" mb-2" />
      <input type="number" name="capacity" placeholder="Capacity" required min="1" class="mb-2" />
      <textarea name="description" placeholder="Description" class="mb-2"></textarea>
      <input type="file" name="image" accept="image/*" class="mb-3" />
      <button type="submit" name="add" class="btn btn-primary">Add Venue</button>
    </form>

    <!-- Venue List Table -->
    <div class="venue-table-wrapper">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="id-col">ID</th>
            <th class="name-col">Name</th>
            <th class="location-col">Location</th>
            <th class="capacity-col">Capacity</th>
            <th class="description-col">Description</th>
            <th class="image-col">Image</th>
            <th class="actions-col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($venue = mysqli_fetch_assoc($venues)) : ?>
            <tr>
              <td class="id-col"><?= htmlspecialchars($venue['id']) ?></td>
              <td class="name-col"><?= htmlspecialchars($venue['name']) ?></td>
              <td class="location-col"><?= htmlspecialchars($venue['location']) ?></td>
              <td class="capacity-col"><?= intval($venue['capacity']) ?></td>
              <td class="description-col"><?= nl2br(htmlspecialchars($venue['description'])) ?></td>
              <td class="image-col">
                <?php if ($venue['image'] && file_exists("../" . $venue['image'])): ?>
                  <img src="../<?= htmlspecialchars($venue['image']) ?>" alt="Venue Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px;" />
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td class="actions-col">
                <a href="edit-venue.php?id=<?= htmlspecialchars($venue['id']) ?>" class="btn btn-sm btn-warning me-2 m-1">
                  <i class="bi bi-pencil-square m-1"></i> Edit
                </a>
                <a href="delete-venue.php?id=<?= htmlspecialchars($venue['id']) ?>" 
                   class="btn btn-sm btn-danger m-1" 
                   onclick="return confirm('Are you sure you want to delete this venue?');">
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

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
