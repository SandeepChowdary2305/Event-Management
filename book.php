<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db.php");

// If not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get event ID from GET param
if (!isset($_GET['event_id'])) {
    die("Event not selected.");
}

$event_id = intval($_GET['event_id']); // sanitize input

// Fetch event name using event_id
$stmtEvent = $conn->prepare("SELECT name FROM event_types WHERE id = ?");
$stmtEvent->bind_param("i", $event_id);
$stmtEvent->execute();
$stmtEvent->bind_result($event_name);
$stmtEvent->fetch();
$stmtEvent->close();

if (!$event_name) {
    die("Event not found.");
}

// Fetch venues for dropdown
$venueOptions = "";
$venueQuery = $conn->query("SELECT id, name FROM venues");

if (!$venueQuery) {
    die("Venue Query Failed: " . $conn->error);
}

while ($venue = $venueQuery->fetch_assoc()) {
    $venueOptions .= "<option value='{$venue['id']}'>" . htmlspecialchars($venue['name']) . "</option>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['email'];

    // Fetch user_id
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->bind_param("s", $user_email);
    $stmtUser->execute();
    $stmtUser->bind_result($user_id);
    $stmtUser->fetch();
    $stmtUser->close();

    // Get form values
    $event_type = $_POST['event_type'];
    $event_date = $_POST['event_date'];
    $venue_id = $_POST['venue_id'];
    $services = $_POST['services'];
    $status = 'pending';
    $created_at = date('Y-m-d');

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_type, event_date, venue_id, services, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $user_id, $event_type, $event_date, $venue_id, $services, $status, $created_at);
    
    if ($stmt->execute()) {
        echo "<script>alert('Event booked successfully!'); window.location.href='user.php';</script>";
        exit();
    } else {
        echo "<script>alert('Booking failed. Try again.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Event</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .book-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            font-family: 'Segoe UI', sans-serif;
        }

        .book-form-container h2 {
            text-align: center;
            color: #006EA8;
            margin-bottom: 20px;
        }

        .book-form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .book-form-container label {
            font-weight: 600;
            color: #003a63;
        }

        .book-form-container input[type="text"],
        .book-form-container input[type="date"],
        .book-form-container select,
        .book-form-container textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            font-family: 'Segoe UI', sans-serif;
        }

        .book-form-container input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .book-form-container input[type="submit"] {
            background-color: #006EA8;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 700;
        }

        .book-form-container input[type="submit"]:hover {
            background-color: #004f7c;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="book-form-container">
    <h2>Book Your Event</h2>
    <form method="POST" action="">
        <label>Event Type</label>
        <input type="text" name="event_type" value="<?= htmlspecialchars($event_name) ?>" readonly required>

        <label>Event Date</label>
        <input type="date" name="event_date" required>

        <label>Select Venue</label>
        <select name="venue_id" required>
            <option value="">-- Choose Venue --</option>
            <?= $venueOptions ?>
        </select>

        <label>Services Required</label>
        <textarea name="services" rows="4" required></textarea>

        <input type="submit" value="Book Event">
    </form>
</div>

</body>
</html>
