<?php
// Future use: session_start(); to redirect logged-in users
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Management System</title>
  <link rel="stylesheet" href="style/style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <!-- Hero Section -->
  <div class="hero">
    <div>
      <h2>Welcome to Our Event Platform</h2>
      <p>
        From corporate meetups to college fests, we bring your events to life with powerful planning tools and smooth execution.
      </p>
      <a href="login.php" class="btn">Get Started</a>
    </div>
  </div>

  <!-- Features Section -->
  <section class="features">
    <div class="feature-box">
      <h3>Event Planning</h3>
      <p>Create and customize events with scheduling, categories, and target audience.</p>
    </div>
    <div class="feature-box">
      <h3>Guest Management</h3>
      <p>Invite, manage, and track RSVPs with ease and professionalism.</p>
    </div>
    <div class="feature-box">
      <h3>Real-Time Updates</h3>
      <p>Live event updates, reminders, and announcements to keep everyone informed.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; <?php echo date("Y"); ?> Event Management System. All rights reserved.
  </footer>
</body>
</html>
