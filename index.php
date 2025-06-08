
<?php
// You can add session_start() here in future for login system
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Management System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: linear-gradient(to right, #f3e5f5, #e1f5fe);
      color: #333;
    }

    header {
      background: #6a1b9a;
      color: #fff;
      padding: 30px 0;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    header h1 {
      font-size: 3rem;
      letter-spacing: 2px;
    }

    header p {
      font-size: 1.2rem;
      margin-top: 10px;
    }

    nav {
      background: #4a148c;
      display: flex;
      justify-content: center;
      padding: 12px 0;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 20px;
      font-size: 18px;
      transition: 0.3s;
    }

    nav a:hover {
      color: #ffeb3b;
    }

    .hero {
      background: url('https://images.unsplash.com/photo-1582719478250-c89132bca05b') no-repeat center center/cover;
      height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      padding: 0 20px;
      animation: slideIn 1.2s ease-out;
    }

    .hero h2 {
      font-size: 3rem;
      margin-bottom: 20px;
      text-shadow: 2px 2px 8px black;
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 700px;
      text-shadow: 1px 1px 6px black;
    }

    .btn {
      display: inline-block;
      margin-top: 30px;
      padding: 14px 30px;
      background-color: #ce93d8;
      color: black;
      text-decoration: none;
      font-weight: bold;
      font-size: 18px;
      border-radius: 30px;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background-color: #ab47bc;
      color: white;
    }

    section.features {
      display: flex;
      justify-content: space-around;
      padding: 50px 30px;
      background: #f8f9fa;
    }

    .feature-box {
      background: white;
      border-radius: 12px;
      padding: 30px;
      width: 28%;
      text-align: center;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .feature-box:hover {
      transform: translateY(-10px);
    }

    .feature-box h3 {
      color: #6a1b9a;
      margin-bottom: 15px;
    }

    footer {
      background: #4a148c;
      color: white;
      text-align: center;
      padding: 20px;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0px);
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Event Management System</h1>
    <p>Organize, Manage, and Celebrate Events with Ease</p>
  </header>

  <nav>
    <a href="#">Home</a>
    <a href="#">Login</a>
    <a href="#">Register</a>
    <a href="#">View Events</a>
    <a href="#">Contact Us</a>
  </nav>

  <div class="hero">
    <div>
      <h2>Welcome to Our Event Platform</h2>
      <p>From corporate meetups to college fests, we bring your events to life with powerful planning tools and smooth execution.</p>
      <a href="login.php" class="btn">Get Started</a>
    </div>
  </div>

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

  <footer>
    &copy; <?php echo date("Y"); ?> Event Management System. All rights reserved.
  </footer>

</body>
</html>

