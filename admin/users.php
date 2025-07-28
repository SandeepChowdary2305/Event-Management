<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: users.php");
    exit();
}

$result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Users | Admin - Celebria</title>
  <link rel="stylesheet" href="style/users.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body>

  <div class="user-admin-wrapper">
    <!-- Sidebar -->
    <aside class="user-sidebar">
      <img src="../media/logo.png" alt="Admin Logo" />
      <nav>
        <a href="admin.php">Dashboard</a>
        <a href="users.php" style="background:#fff; color:#006EA8; font-weight:bold;">Users</a>
        <a href="manage-events.php">Events</a>
        <a href="manage-venues.php">Venues</a>
        <a href="manage-bookings.php">Bookings</a>
        <a href="contact_message.php">Contact</a>
        <a href="../logout.php">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="user-main">
      <h1>All Registered Users</h1>

      <div class="user-search-box">
        <input type="text" id="userSearch" placeholder="Search by name or email...">
      </div>

      <div class="user-table-container">
        <table class="user-table" id="userTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($user = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                  <?php if ($_SESSION['username'] !== $user['name']): ?>
                    <a href="users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="user-delete-btn">
    <i class="bi bi-trash"></i>
</a>
                  <?php else: ?>
                    <span class="user-note">Self</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script>
    const searchInput = document.getElementById("userSearch");
    searchInput.addEventListener("keyup", function () {
      const filter = searchInput.value.toLowerCase();
      const rows = document.querySelectorAll("#userTable tbody tr");
      rows.forEach(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const email = row.cells[2].innerText.toLowerCase();
        row.style.display = (name.includes(filter) || email.includes(filter)) ? "" : "none";
      });
    });
  </script>

</body>
</html>
