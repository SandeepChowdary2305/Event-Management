<?php
session_start();
require_once 'db.php'; // Make sure this file connects to ems_db

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered.";
        } else {
            // Hash password and insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // default role
            $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $name, $email, $hashed, $role);
            if ($insert->execute()) {
                $_SESSION['username'] = $name;
                $_SESSION['role'] = $role;
                header("Location: login.php");
                exit();
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Event Management System</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>

    <div class="container" style="max-width: 500px; margin: 40px auto;">
        <?php if ($message): ?>
            <p style="color:red;"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Full Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Confirm Password:</label><br>
            <input type="password" name="confirm" required><br><br>

            <button type="submit" class="btn">Register</button>
        </form>

        <p style="margin-top: 20px;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
