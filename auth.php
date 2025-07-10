<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db.php");

// Admin credentials
$admin_email = "admin@ems.com";
$admin_pass_plain = "admin123";

// Get form inputs
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pass = isset($_POST['password']) ? trim($_POST['password']) : '';

// Admin login (optional: you can move this to DB logic too)
if ($email === $admin_email && $pass === $admin_pass_plain) {
    $_SESSION['role'] = 'admin';
    $_SESSION['username'] = 'Admin';
    header("Location: admin/admin.php");
    exit();
}

// DB user login
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($pass, $user['password'])) {
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['name']; // You said show name after login
        $_SESSION['email'] = $user['email'];
        header("Location: user.php");
        exit();
    } else {
        echo "<script>alert('Incorrect password.'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Email not registered. Please sign up.'); window.location.href='login.php';</script>";
}

$stmt->close();
$conn->close();
?>
