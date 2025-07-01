<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <style>
    body {
      margin: 0;
      padding: 50px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('media/login.jpg');
      background-size: cover;
      height: 100vh;
      display: flex;
      flex-direction:column;
      align-items: center;
      background-position: center;
    }

    .login-container {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      width: 320px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 90%;
      padding: 10px 12px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #5c6bf3;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #3949ab;
    }
    img{
        width: 250px;
        height: auto;
        margin-bottom: 50px;

    }
    a {
    color: #5c6bf3;
    text-decoration: none;
}
  </style>
</head>

<body>
    <img src="media/logo.png"/>
  <div class="login-container">
    <h2>Login</h2>
    <form action="auth.php" method="post">
      Username: <input type="text" name="username" required><br>
      Password: <input type="password" name="password" required><br>
      <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>
