<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['new_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['new_status'];

    if (in_array($new_status, ['accepted', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $booking_id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage-bookings.php");
        exit();
    }
}

// Adjusted query: bookings.event_type assumed to be event name text
$sql = "
    SELECT b.id AS booking_id, u.name AS username, u.email, b.event_type AS event_name, v.name AS venue_name, v.location,
           b.event_date, b.services, b.status, b.created_at
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN venues v ON b.venue_id = v.id
    ORDER BY b.created_at DESC
";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching bookings: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Bookings | Admin - Celebria</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="style/admin.css" />
  <style>
    .event-main-content table.table th,
    .event-main-content table.table td {
      text-align: center;
      vertical-align: middle;
    }
    .btn-accept {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      font-size: 14px;
    }
    .btn-reject {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      font-size: 14px;
    }
    .btn-accept:hover {
      background-color: #218838;
      color: white;
    }
    .btn-reject:hover {
      background-color: #c82333;
      color: white;
    }
    .status-badge {
      padding: 5px 12px;
      border-radius: 12px;
      font-weight: 600;
      text-transform: capitalize;
      display: inline-block;
      min-width: 80px;
    }
    .status-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .status-accepted {
      background-color: #28a745;
      color: white;
    }
    .status-rejected {
      background-color: #dc3545;
      color: white;
    }
    form.inline-form {
      display: inline-block;
      margin: 0 5px;
    }
  </style>
</head>
<body>

<div class="event-admin-wrapper">
  <aside class="event-sidebar">
    <img src="../media/logo.png" alt="Admin Logo" />
    <nav>
      <a href="admin.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="manage-events.php">Events</a>
      <a href="manage-venues.php">Venues</a>
      <a href="manage-bookings.php" style="background:#fff; color:#006EA8; font-weight:bold;">Bookings</a>
      <a href="contact_message.php">Contact</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </aside>

  <main class="event-main-content p-4">
    <h2>Manage Bookings</h2>

    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>User Name</th>
          <th>Email</th>
          <th>Event</th>
          <th>Venue</th>
          <th>Venue Location</th>
          <th>Event Date</th>
          <th>Services</th>
          <th>Status</th>
          <th style="min-width:120px;">Action</th>
          <th>Booked On</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($booking = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
            <td><?= htmlspecialchars($booking['username']) ?></td>
            <td><?= htmlspecialchars($booking['email']) ?></td>
            <td><?= htmlspecialchars($booking['event_name']) ?></td>
            <td><?= htmlspecialchars($booking['venue_name']) ?></td>
            <td><?= htmlspecialchars($booking['location']) ?></td>
            <td><?= htmlspecialchars($booking['event_date']) ?></td>
            <td><?= nl2br(htmlspecialchars($booking['services'])) ?></td>
            <td>
              <span class="status-badge status-<?= htmlspecialchars($booking['status']) ?>">
                <?= htmlspecialchars($booking['status']) ?>
              </span>
            </td>
            <td>
              <?php if ($booking['status'] === 'pending'): ?>
                <form method="POST" class="inline-form">
                  <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>" />
                  <input type="hidden" name="new_status" value="accepted" />
                  <button type="submit" class="btn btn-accept btn-sm" title="Accept">
                    <i class="bi bi-check-lg"></i>
                  </button>
                </form>
                <form method="POST" class="inline-form">
                  <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>" />
                  <input type="hidden" name="new_status" value="rejected" />
                  <button type="submit" class="btn btn-reject btn-sm" title="Reject">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </form>
              <?php else: ?>
                <em>No actions available</em>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($booking['created_at']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
