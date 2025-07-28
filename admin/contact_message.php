<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch contact messages
$result = $conn->query("SELECT id, name, email, subject, message, created_at FROM contact_messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Messages | Admin - Celebria</title>
    <link rel="stylesheet" href="style/user.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <style>
    /* Container & layout */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      color: #333;
    }
    .user-admin-wrapper {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .user-sidebar {
      width: 240px;
      background-color: #006EA8;
      color: white;
      padding: 25px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }
    .user-sidebar img {
      max-width: 140px;
      margin-bottom: 40px;
    }
    .user-sidebar nav {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .user-sidebar nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      padding: 10px 15px;
      border-radius: 6px;
      display: block;
      transition: background-color 0.3s ease;
    }
    .user-sidebar nav a:hover {
      background-color: #005687;
      color: #e0e7f4;
    }
    .user-sidebar nav a[style] {
      background: #fff !important;
      color: #006EA8 !important;
      font-weight: 700 !important;
    }

    /* Main content */
    .user-main {
      margin-left: 240px;
      padding: 40px 50px;
      flex-grow: 1;
      background: white;
      box-shadow: 0 4px 20px rgb(0 110 168 / 0.1);
      border-radius: 12px;
      margin-bottom: 40px;
    }
    .user-main h1 {
      color: #006EA8;
      font-weight: 700;
      margin-bottom: 30px;
      font-size: 32px;
      text-align: center;
    }

    /* Table styling */
    .user-table-container {
      overflow-x: auto;
    }
    table.user-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    table.user-table thead {
      background-color: #f1faff;
    }
    table.user-table th,
    table.user-table td {
      padding: 14px 12px;
      border: 1px solid #d9e6f5;
      text-align: left;
      vertical-align: top;
    }
    table.user-table th {
      color: #006EA8;
      font-weight: 700;
      text-align: center;
    }
    table.user-table tbody tr:hover {
      background-color: #eaf4fc;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .user-main {
        margin-left: 0;
        padding: 20px 15px;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

<div class="user-admin-wrapper">
  <!-- Sidebar -->
  <aside class="user-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="manage-events.php">Events</a>
      <a href="manage-venues.php">Venues</a>
      <a href="manage-bookings.php">Bookings</a>
      <a href="contact_message.php" style="background:#fff; color:#006EA8; font-weight:bold;">Contact</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="user-main">
    <h1>Contact Messages</h1>

    <?php if ($result->num_rows === 0): ?>
      <p style="text-align:center; font-size:1.1rem; color:#666;">No contact messages received yet.</p>
    <?php else: ?>
      <div class="user-table-container">
        <table class="user-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Received On</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($msg = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $msg['id'] ?></td>
                <td><?= htmlspecialchars($msg['name']) ?></td>
                <td><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= htmlspecialchars($msg['subject']) ?></td>
                <td style="white-space: pre-line;"><?= htmlspecialchars($msg['message']) ?></td>
                <td><?= htmlspecialchars($msg['created_at']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
</div>

</body>
</html>
