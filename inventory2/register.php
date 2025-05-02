<?php
require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check for duplicates
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Username already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Registration failed.";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .container {
            background:rgb(0, 0, 0);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0px 0px 20px 10px rgb(197, 197, 197);
            width: 350px;
            opacity: 95%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            font-family: 'Poppins', sans-serif;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solidrgb(255, 255, 255);
            border-radius: 8px;
            box-sizing: border-box;
            border: 1px solid white;
            background-color: black;
            color: white;
        }

        input[type="submit"] {
            width: 100%;
            background-color:rgb(0, 0, 0);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        input[type="submit"]:hover {
            background-color:rgb(72, 73, 73);
        }

        .message {
            margin-top: 15px;
            text-align: center;
            color: red;
        }

        .success {
            color: green;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <input type="submit" value="Register">
    </form>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successful') !== false ? 'success' : '' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>

</body>
</html>
