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

// Get current event to delete image
$result = mysqli_query($conn, "SELECT image FROM event_types WHERE id = $id");
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo "Event not found!";
    exit();
}

// Delete image file if exists
if (!empty($event['image']) && file_exists($event['image'])) {
    unlink($event['image']);
}

// Delete from DB
mysqli_query($conn, "DELETE FROM event_types WHERE id = $id");

// Redirect
header("Location: manage-events.php");
exit();
?>
