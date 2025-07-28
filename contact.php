<?php
include 'db.php'; // your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $success_message = "Your message has been sent successfully.";
        } else {
            $error_message = "Failed to send message. Please try again.";
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us - Event Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="style/style.css" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f0faff, #ffffff);
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .contact-container {
      max-width: 700px;
      margin: 60px auto 40px auto;
      padding: 40px 30px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 110, 168, 0.1);
      animation: fadeIn 0.6s ease-in-out;
      flex-grow: 1;
    }

    .contact-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #006EA8;
      font-weight: 600;
      font-size: 26px;
    }

    .form-group label {
      font-weight: 600;
      color: #555;
    }

    .form-control:focus {
      border-color: #006EA8;
      box-shadow: 0 0 6px rgba(0, 110, 168, 0.3);
    }

    .submit-btn {
      background-color: #006EA8;
      color: white;
      padding: 14px 36px;
      border: none;
      font-size: 18px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease, transform 0.2s ease;
      user-select: none;
      display: block;
      margin: 10px auto 0 auto;
    }

    .submit-btn:hover {
      background-color: #004e7c;
      transform: translateY(-2px);
    }

    footer {
      background-color: #006EA8;
      color: #fff;
      text-align: center;
      padding: 16px 0;
      font-weight: 600;
      user-select: none;
      margin-top: auto;
      box-shadow: 0 -2px 6px rgba(0,110,168,0.3);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="contact-container">
  <h2>We'd love to hear from you</h2>
  <form action="#" method="POST">
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" name="name" id="name" class="form-control" placeholder="Your full name" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Your email" required>
    </div>

    <div class="mb-3">
      <label for="subject" class="form-label">Subject</label>
      <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject of your message" required>
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Your Message</label>
      <textarea name="message" id="message" class="form-control" placeholder="Write your message here..." rows="5" required></textarea>
    </div>

    <button type="submit" class="submit-btn">Send Message</button>
  </form>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> Event Management System. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
