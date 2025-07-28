<?php
include '../db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-venues.php");
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM venues WHERE id = $id");
$venue = mysqli_fetch_assoc($result);

if (!$venue) {
    echo "Venue not found!";
    exit();
}

// Update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $capacity = intval($_POST['capacity']);
    $description = $_POST['description'];
    $imagePath = $venue['image'];

    // New image uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/venues/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
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

    $stmt = $conn->prepare("UPDATE venues SET name = ?, location = ?, capacity = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $name, $location, $capacity, $description, $imagePath, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-venues.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Venue | Admin - Celebria</title>
  <link rel="stylesheet" href="style/admin.css" />
</head>
<body>

<div class="event-admin-wrapper">
  <aside class="event-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="manage-event-types.php">Events</a>
      <a href="manage-venues.php" style="background:#fff; color:#006EA8; font-weight:bold;">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <main class="event-main-content">
    <h2>Edit Venue</h2>

    <form method="POST" enctype="multipart/form-data" class="event-form">
      <input type="text" name="name" value="<?= htmlspecialchars($venue['name']) ?>" required />
      <input type="text" name="location" value="<?= htmlspecialchars($venue['location']) ?>" required />
      <input type="number" name="capacity" value="<?= htmlspecialchars($venue['capacity']) ?>" required />
      <textarea name="description"><?= htmlspecialchars($venue['description']) ?></textarea>
      <input type="file" name="image" accept="image/*" />
      <?php if (!empty($venue['image'])): ?>
        <p>Current Image:</p>
        <img src="<?= $venue['image'] ?>" alt="Venue Image" width="100">
      <?php endif; ?>
      <button type="submit">Update</button>
    </form>
  </main>
</div>

</body>
</html>
