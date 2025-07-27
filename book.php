<?php
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['email'];

    // Fetch user_id using email
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
    <link rel="stylesheet" href="style/style.css"> <!-- Your existing stylesheet -->
    <style>
        .book-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            animation: slideIn 0.8s ease-out;
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

        .book-form-container input[type="text"],
        .book-form-container input[type="date"],
        .book-form-container input[type="number"],
        .book-form-container textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .book-form-container textarea {
            resize: vertical;
        }

        .book-form-container input[type="submit"] {
            background-color: #006EA8;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
        <input type="text" name="event_type" required>

        <label>Event Date</label>
        <input type="date" name="event_date" required>

        <label>Venue ID</label>
        <input type="number" name="venue_id" required>

        <label>Services Required</label>
        <textarea name="services" rows="4" required></textarea>

        <input type="submit" value="Book Event">
    </form>
</div>

</body>
</html>
