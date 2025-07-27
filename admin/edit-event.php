<?php
include '../db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../user.php");
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM event_types WHERE id = $id");
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo "Event not found!";
    exit();
}

// Update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $imagePath = $event['image'];

    // New image uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Delete old image if exists
            if (!empty($imagePath) && file_exists($imagePath)) {
                unlink($imagePath);
            }
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("UPDATE event_types SET name = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $description, $imagePath, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event Type | Admin - Celebria</title>
  <link rel="stylesheet" href="style/admin.css" />
</head>
<body>

<div class="event-admin-wrapper">
  <aside class="event-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php" style="background:#fff; color:#006EA8; font-weight:bold;">Users</a>
      <a href="manage-event-types.php">Events</a>
      <a href="manage-venues.php">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <main class="event-main-content">
    <h2>Edit Event Type</h2>

    <form method="POST" enctype="multipart/form-data" class="event-form">
      <input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required />
      <textarea name="description"><?= htmlspecialchars($event['description']) ?></textarea>
      <input type="file" name="image" accept="image/*" />
      <?php if (!empty($event['image'])): ?>
        <p>Current Image:</p>
        <img src="<?= $event['image'] ?>" alt="Event Image" width="100">
      <?php endif; ?>
      <button type="submit">Update</button>
    </form>
  </main>
</div>

</body>
</html>
