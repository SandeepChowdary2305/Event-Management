<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
  <div class="hd-container">
    <div>
      <img class="logo" src="media/logo.png" alt="Logo"/>
    </div>
    <div class="header">
      <h1>CELEBRIA</h1>
      <p>Where Every Occasion Finds Perfect Planning</p>
    </div>
  </div>

  <nav>
    <div class="nav-center">
      <ul>
        <?php if (isset($_SESSION['username'])): ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="events.php">View Events</a></li>
          <li><a href="venues.php">Venues</a></li> <!-- Added Venues link -->
          <li><a href="contact.php">Contact Us</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="nav-right">
      <ul>
        <?php if (isset($_SESSION['username'])): ?>
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="admin/admin.php">Admin Panel</a></li>
          <?php else: ?>
            <li><a href="user.php">Dashboard</a></li>
          <?php endif; ?>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>
