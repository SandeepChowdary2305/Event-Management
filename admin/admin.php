<?php
// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include '../db.php';  // Ensure this file exists and has valid DB connection

// Fetch counts for dashboard cards (with error checks)
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;
$event_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT event_type) AS total FROM bookings"))['total'] ?? 0;
$venue_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM venues"))['total'] ?? 0;
$booking_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'] ?? 0;

// Fetch data for bookings by event type chart
$chart_data = [];
$chart_query = mysqli_query($conn, "SELECT event_type, COUNT(*) as count FROM bookings GROUP BY event_type");

if ($chart_query) {
    while ($row = mysqli_fetch_assoc($chart_query)) {
        $chart_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/admin.css" />
    <title>Admin Dashboard - Celebria</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <img src="../media/logo.png" alt="Celebria Logo" />
            <nav>
                <a href="admin.php">Dashboard</a>
                <a href="manage-users.php">Users</a>
                <a href="manage-events.php">Events</a>
                <a href="manage-venues.php">Venues</a>
                <a href="manage-bookings.php">Bookings</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <h1>Welcome Admin</h1>
            <section class="cards">
                <div class="card">
                    <h2><?php echo $user_count; ?></h2>
                    <p>Total Users</p>
                </div>
                <div class="card">
                    <h2><?php echo $event_count; ?></h2>
                    <p>Event Types</p>
                </div>
                <div class="card">
                    <h2><?php echo $venue_count; ?></h2>
                    <p>Venues</p>
                </div>
                <div class="card">
                    <h2><?php echo $booking_count; ?></h2>
                    <p>Total Bookings</p>
                </div>
            </section>

            <!-- Bookings by Event Type Chart -->
            <section class="chart-container">
                <h2>Bookings by Event Type</h2>
                <canvas id="bookingChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('bookingChart').getContext('2d');
        const bookingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach ($chart_data as $data) {
                        echo "'" . htmlspecialchars($data['event_type']) . "',";
                    } ?>
                ],
                datasets: [{
                    label: 'Number of Bookings',
                    data: [
                        <?php foreach ($chart_data as $data) {
                            echo (int)$data['count'] . ",";
                        } ?>
                    ],
                    backgroundColor: '#3F51B5',
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>

</body>
</html>
