<?php
session_start();
require 'db.php';

$error = "";

// Initialize attempts and lock time
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}
if (!isset($_SESSION['lock_time'])) {
    $_SESSION['lock_time'] = null;
}

// Check if user is locked
$locked = false;
if ($_SESSION['attempts'] >= 3) {
    $locked = true;
    if (time() - $_SESSION['lock_time'] >= 10) {
        $_SESSION['attempts'] = 0;
        $_SESSION['lock_time'] = null;
        $locked = false;
    } else {
        $remaining = 10 - (time() - $_SESSION['lock_time']);
        $_SESSION['remaining_time'] = $remaining;
        $error = "⏳ Too many attempts. Try again in {$remaining} second(s).";
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['attempts'] = 0;
            $_SESSION['lock_time'] = null;

            $stmt->close();
            $conn->close();

            header("Location: " . ($role === 'admin' ? 'dashboard.php' : 'user.php'));
            exit;
        }
    }

    $_SESSION['attempts']++;
    if ($_SESSION['attempts'] >= 3) {
        $_SESSION['lock_time'] = time();
        $error = "⛔ Too many failed attempts. Please wait 10 seconds.";
    } else {
        $remaining = 3 - $_SESSION['attempts'];
        $error = "❌ Invalid credentials. Attempts left: $remaining";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --text-main: #355B3E;
      --text-muted: #58745E;
      --white: #FFFFFF;
      --primary: #029664;
    }

    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}
    h3{
        font-size: 13px;
        margin-left: 2px;
        margin-bottom: 3px;
        font-family: "Inter", sans-serif;
        color: #355B3E;
    }
    h1{
        font-size: 16px;
        margin-left: 2px;
        font-family: "Inter", sans-serif;
        color: #355B3E;
        font-weight: 600;
    }

    body {
      font-family: "Inter", sans-serif;
      background-color: #355B3E;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      display: flex;
      width: 100%;
      max-width: 900px;
      height: 570px;
      background: var(--white);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .login-form {
      margin-top: 60px;
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-form h1 {
      font-size: 24px;
      color: var(--text-main);
      margin-bottom: 10px;
    }

    .login-form p {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 20px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #f9f9f9;
    }

    .buttons {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .buttons button {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
    }

    .login-btn {
      background-color: var(--primary);
      color: var(--white);
    }

    .signup-btn {
      background-color: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .social-login {
      text-align: center;
      font-size: 14px;
      color: var(--text-muted);
      margin-left: -55px;
    }

    .social-login a {
      margin: 0 15px;
      text-decoration: none;
      color: var(--primary);
    }

    .illustration {
      flex: 1;
      background: #fef1e8;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .illustration img {
      width: 80%;
      max-width: 300px;
      height: auto;
    }

    .error {
      color: red;
      font-size: 13px;
      margin-bottom: 10px;
    }
    h2{
      margin-top: -60px;
      margin-bottom: 30px;
    }
    .remember-forgot {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12.5px;
  margin-bottom: 20px;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.remember-me input[type="checkbox"] {
  transform: translateY(0.4px); /* Align checkbox more precisely */
  cursor: pointer;
}

.forgot-link {
  color: var(--primary);
  text-decoration: none;
  font-weight: 500;
  margin-right: 15px;
}
.forgot-link:hover {
  text-decoration: underline;
}


  </style>
</head>
<body>

<div class="container">
  <div class="login-form">
    <h2> (Company Name) </h2>
    <h1>Streamline your inventory with Ease</h1>
    <p>Welcome Back, Please login to your account</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <h3> Email </h3>
      <input type="text" name="username" placeholder="Email" required <?= $locked ? 'disabled' : '' ?>>
      <h3> Password </h3>
      <input type="password" name="password" placeholder="Password" required <?= $locked ? 'disabled' : '' ?>>
      <h3> Role </h3>
<select name="role" required <?= $locked ? 'disabled' : '' ?> style="padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #ccc; background: #f9f9f9; width: 100%;">
  <option value="" disabled selected>Select Role</option>
  <option value="user">User</option>
  <option value="admin">Admin</option>
</select>

      <div class="remember-forgot">
  <label class="remember-me">
    <input type="checkbox" name="remember" />
    Remember me
  </label>
  <a class="forgot-link" href="#">Forgot password?</a>
</div>



      <div class="buttons">
        <button type="submit" class="login-btn" <?= $locked ? 'disabled' : '' ?>>Login</button>
        <button type="button" class="signup-btn" onclick="location.href='register.php';">Sign Up</button>
      </div>

      <div class="social-login">
        Or, login with
        <a href="#">Facebook</a>
        <a href="#">LinkedIn</a>
        <a href="#">Google</a>
      </div>
    </form>
  </div>

  <div class="illustration">
    <img src="images/illustration.png" alt="Travel Illustration">
  </div>
</div>

<?php if ($locked): ?>
<script>
  let seconds = <?= $_SESSION['remaining_time'] ?? 10 ?>;
  const errorDiv = document.querySelector(".error");

  const interval = setInterval(() => {
    seconds--;
    if (seconds > 0) {
      errorDiv.textContent = `⏳ Please wait ${seconds} second(s)...`;
    } else {
      clearInterval(interval);
      errorDiv.textContent = "✅ You can try logging in now.";
      document.querySelectorAll("input, select, button").forEach(el => el.disabled = false);
    }
  }, 1000);
</script>
<?php endif; ?>

</body>
</html>

