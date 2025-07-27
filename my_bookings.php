<?php
session_start();
require_once 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.event_type, b.event_date, v.name AS venue_name, b.services, b.status, b.created_at
        FROM bookings b
        LEFT JOIN venues v ON b.venue_id = v.id
        WHERE b.user_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      padding: 30px;
      margin: 0;
      animation: fadeInBody 0.8s ease-in;
    }

    h2 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 30px;
      animation: slideDown 0.7s ease-in-out;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      animation: fadeInUp 1s ease;
    }

    th, td {
      padding: 14px 18px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      transition: background-color 0.3s ease;
    }

    th {
      background: #2980b9;
      color: white;
      font-weight: 600;
    }

    tr:hover {
      background-color: #f0f8ff;
    }

    .status {
      font-weight: bold;
      padding: 6px 12px;
      border-radius: 20px;
      display: inline-block;
      font-size: 14px;
      animation: fadeIn 0.5s ease-in;
    }

    .status.Pending {
      background-color: #ffe082;
      color: #795548;
    }

    .status.Approved {
      background-color: #81c784;
      color: #1b5e20;
    }

    .status.Rejected {
      background-color: #e57373;
      color: #b71c1c;
    }

    p {
      text-align: center;
      font-size: 18px;
      color: #555;
    }

    /* Animations */
    @keyframes fadeInBody {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideDown {
      0% { transform: translateY(-20px); opacity: 0; }
      100% { transform: translateY(0); opacity: 1; }
    }

    @keyframes fadeInUp {
      0% { transform: translateY(30px); opacity: 0; }
      100% { transform: translateY(0); opacity: 1; }
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: scale(0.9); }
      100% { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

  <h2>My Bookings</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Event Type</th>
          <th>Date</th>
          <th>Venue</th>
          <th>Services</th>
          <th>Status</th>
          <th>Booked At</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['event_type']); ?></td>
            <td><?php echo htmlspecialchars($row['event_date']); ?></td>
            <td><?php echo htmlspecialchars($row['venue_name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['services']); ?></td>
            <td><span class="status <?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No bookings found.</p>
  <?php endif; ?>

</body>
</html>
