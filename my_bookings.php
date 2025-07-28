<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

include 'db.php';

$username = $_SESSION['username'] ?? '';
if (!$username) {
    die("No username found in session.");
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Use the correct column name here (assumed 'name')
$stmt = $conn->prepare("SELECT id FROM users WHERE name = ?");
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    die("User ID not found for username/name: " . htmlspecialchars($username));
}

$sql = "
    SELECT b.id AS booking_id, b.event_type AS event_name, v.name AS venue_name, v.location,
           b.event_date, b.services, b.status, b.created_at
    FROM bookings b
    JOIN venues v ON b.venue_id = v.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Bookings | User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f0faff, #ffffff);
      color: #333;
    }
    .dashboard {
      max-width: 900px;
      margin: 80px auto;
      padding: 40px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 110, 168, 0.1);
      animation: fadeIn 0.6s ease-in-out;
      text-align: center;
    }
    .dashboard h2 {
      color: #006EA8;
      font-size: 28px;
      margin-bottom: 20px;
    }
    .status-badge {
      padding: 5px 12px;
      border-radius: 12px;
      font-weight: 600;
      text-transform: capitalize;
      display: inline-block;
      min-width: 90px;
      text-align: center;
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
    table.table {
      margin-top: 20px;
    }
    .btn-primary {
      background-color: #006EA8;
      border-color: #006EA8;
      padding: 12px 30px;
      font-weight: 500;
      border-radius: 8px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-primary:hover {
      background-color: #004e7c;
      transform: translateY(-2px);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="dashboard">
  <h2>My Bookings</h2>

  <?php if ($result->num_rows === 0): ?>
    <div class="alert alert-warning text-center fs-5">
      You currently have no event bookings. Explore our events and book your next experience today!
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Venue</th>
            <th>Location</th>
            <th>Event Date</th>
            <th>Services</th>
            <th>Status</th>
            <th>Booked On</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; ?>
          <?php while ($booking = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $count++; ?></td>
              <td><?= htmlspecialchars($booking['event_name']); ?></td>
              <td><?= htmlspecialchars($booking['venue_name']); ?></td>
              <td><?= htmlspecialchars($booking['location']); ?></td>
              <td><?= htmlspecialchars($booking['event_date']); ?></td>
              <td style="white-space: pre-line;"><?= htmlspecialchars($booking['services']); ?></td>
              <td>
                <span class="status-badge status-<?= htmlspecialchars($booking['status']); ?>">
                  <?= htmlspecialchars($booking['status']); ?>
                </span>
              </td>
              <td><?= htmlspecialchars($booking['created_at']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="user.php" class="btn btn-primary">Back to Dashboard</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
