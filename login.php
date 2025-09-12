<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="css/login.css">
  <link rel="icon" href="css/images/logo.jpg">
</head>
<body>
  <div class="login-container">
    <img src="css/images/logo.jpg" alt="logo" id="unitlogo" height="90"><br>
    <p>MMSU-ROTC System</p>

    <?php if (isset($_GET['error'])): ?>
      <p style="color:red;">Invalid username or password!</p>
    <?php endif; ?>

    <form action="backend/login.php" method="POST">
      <div class="form-group">
        <label for="name">Username</label>
        <input type="text" name="username" id="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>
      <button type="submit">Login</button>
    </form>
    <div class="footer-text">
      Strictly for Advance ROTC Officer Access only
    </div>
  </div>
</body>
</html>
