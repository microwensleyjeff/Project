
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
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exported Figma Design</title>
  <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="travalizer-desktop-1">
<div class="main-2">
<div class="illustration-3">
<div class="rectangle-4-4"></div>
<div class="day66travel-5">
<img src="images/vector-6.svg" class="vector-6" alt="vector" />
<img src="images/vector-stroke-7.svg" class="vector-stroke-7" alt="vector-stroke" />
<img src="images/vector-stroke-8.svg" class="vector-stroke-8" alt="vector-stroke" />
<img src="images/vector-stroke-9.svg" class="vector-stroke-9" alt="vector-stroke" />
<img src="images/vector-stroke-10.svg" class="vector-stroke-10" alt="vector-stroke" />
<img src="images/vector-stroke-11.svg" class="vector-stroke-11" alt="vector-stroke" />
<img src="images/vector-stroke-12.svg" class="vector-stroke-12" alt="vector-stroke" />
<img src="images/vector-stroke-13.svg" class="vector-stroke-13" alt="vector-stroke" />
<img src="images/vector-stroke-14.svg" class="vector-stroke-14" alt="vector-stroke" />
<img src="images/vector-stroke-15.svg" class="vector-stroke-15" alt="vector-stroke" />
<img src="images/vector-stroke-16.svg" class="vector-stroke-16" alt="vector-stroke" />
<img src="images/vector-stroke-17.svg" class="vector-stroke-17" alt="vector-stroke" />
<img src="images/vector-stroke-18.svg" class="vector-stroke-18" alt="vector-stroke" />
<img src="images/vector-stroke-19.svg" class="vector-stroke-19" alt="vector-stroke" />
<img src="images/vector-stroke-20.svg" class="vector-stroke-20" alt="vector-stroke" />
<img src="images/vector-stroke-21.svg" class="vector-stroke-21" alt="vector-stroke" />
<img src="images/vector-stroke-22.svg" class="vector-stroke-22" alt="vector-stroke" />
<img src="images/vector-stroke-23.svg" class="vector-stroke-23" alt="vector-stroke" />
<img src="images/vector-stroke-24.svg" class="vector-stroke-24" alt="vector-stroke" />
<img src="images/vector-stroke-25.svg" class="vector-stroke-25" alt="vector-stroke" />
<img src="images/vector-stroke-26.svg" class="vector-stroke-26" alt="vector-stroke" />
<img src="images/vector-stroke-27.svg" class="vector-stroke-27" alt="vector-stroke" />
<img src="images/vector-stroke-28.svg" class="vector-stroke-28" alt="vector-stroke" />
<img src="images/vector-stroke-29.svg" class="vector-stroke-29" alt="vector-stroke" />
<img src="images/vector-stroke-30.svg" class="vector-stroke-30" alt="vector-stroke" />
<img src="images/vector-stroke-31.svg" class="vector-stroke-31" alt="vector-stroke" />
<img src="images/vector-stroke-32.svg" class="vector-stroke-32" alt="vector-stroke" />
<img src="images/vector-stroke-33.svg" class="vector-stroke-33" alt="vector-stroke" />
<img src="images/vector-stroke-34.svg" class="vector-stroke-34" alt="vector-stroke" />
<img src="images/vector-stroke-35.svg" class="vector-stroke-35" alt="vector-stroke" />
<img src="images/vector-stroke-36.svg" class="vector-stroke-36" alt="vector-stroke" />
<img src="images/vector-stroke-37.svg" class="vector-stroke-37" alt="vector-stroke" />
<img src="images/vector-stroke-38.svg" class="vector-stroke-38" alt="vector-stroke" />
<img src="images/vector-stroke-39.svg" class="vector-stroke-39" alt="vector-stroke" />
<img src="images/vector-stroke-40.svg" class="vector-stroke-40" alt="vector-stroke" />
<img src="images/vector-stroke-41.svg" class="vector-stroke-41" alt="vector-stroke" />
<img src="images/vector-stroke-42.svg" class="vector-stroke-42" alt="vector-stroke" />
<img src="images/vector-stroke-43.svg" class="vector-stroke-43" alt="vector-stroke" />
<img src="images/vector-stroke-44.svg" class="vector-stroke-44" alt="vector-stroke" />
<img src="images/vector-stroke-45.svg" class="vector-stroke-45" alt="vector-stroke" />
<img src="images/vector-stroke-46.svg" class="vector-stroke-46" alt="vector-stroke" />
<img src="images/vector-stroke-47.svg" class="vector-stroke-47" alt="vector-stroke" />
<img src="images/vector-stroke-48.svg" class="vector-stroke-48" alt="vector-stroke" />
<img src="images/vector-stroke-49.svg" class="vector-stroke-49" alt="vector-stroke" />
<img src="images/vector-stroke-50.svg" class="vector-stroke-50" alt="vector-stroke" />
<img src="images/vector-stroke-51.svg" class="vector-stroke-51" alt="vector-stroke" />
<img src="images/vector-stroke-52.svg" class="vector-stroke-52" alt="vector-stroke" />
<img src="images/vector-stroke-53.svg" class="vector-stroke-53" alt="vector-stroke" />
<img src="images/vector-stroke-54.svg" class="vector-stroke-54" alt="vector-stroke" />
<img src="images/vector-stroke-55.svg" class="vector-stroke-55" alt="vector-stroke" />
<img src="images/vector-stroke-56.svg" class="vector-stroke-56" alt="vector-stroke" />
<img src="images/vector-stroke-57.svg" class="vector-stroke-57" alt="vector-stroke" />
<img src="images/vector-stroke-58.svg" class="vector-stroke-58" alt="vector-stroke" />
<img src="images/vector-stroke-59.svg" class="vector-stroke-59" alt="vector-stroke" />
<img src="images/vector-stroke-60.svg" class="vector-stroke-60" alt="vector-stroke" />
<img src="images/vector-stroke-61.svg" class="vector-stroke-61" alt="vector-stroke" />
<img src="images/vector-stroke-62.svg" class="vector-stroke-62" alt="vector-stroke" />
<img src="images/vector-stroke-63.svg" class="vector-stroke-63" alt="vector-stroke" />
<img src="images/vector-stroke-64.svg" class="vector-stroke-64" alt="vector-stroke" />
<img src="images/vector-stroke-65.svg" class="vector-stroke-65" alt="vector-stroke" />
<img src="images/vector-stroke-66.svg" class="vector-stroke-66" alt="vector-stroke" />
<img src="images/vector-stroke-67.svg" class="vector-stroke-67" alt="vector-stroke" />
<img src="images/vector-stroke-68.svg" class="vector-stroke-68" alt="vector-stroke" />
<img src="images/vector-stroke-69.svg" class="vector-stroke-69" alt="vector-stroke" />
<img src="images/vector-stroke-70.svg" class="vector-stroke-70" alt="vector-stroke" />
<img src="images/vector-stroke-71.svg" class="vector-stroke-71" alt="vector-stroke" />
<img src="images/vector-stroke-72.svg" class="vector-stroke-72" alt="vector-stroke" />
<img src="images/vector-stroke-73.svg" class="vector-stroke-73" alt="vector-stroke" />
<img src="images/vector-stroke-74.svg" class="vector-stroke-74" alt="vector-stroke" />
<img src="images/vector-stroke-75.svg" class="vector-stroke-75" alt="vector-stroke" />
<img src="images/vector-stroke-76.svg" class="vector-stroke-76" alt="vector-stroke" />
<img src="images/vector-77.svg" class="vector-77" alt="vector" />
<img src="images/vector-stroke-78.svg" class="vector-stroke-78" alt="vector-stroke" />
<img src="images/vector-79.svg" class="vector-79" alt="vector" />
<img src="images/vector-stroke-80.svg" class="vector-stroke-80" alt="vector-stroke" />
<img src="images/vector-81.svg" class="vector-81" alt="vector" />
<img src="images/vector-stroke-82.svg" class="vector-stroke-82" alt="vector-stroke" />
<img src="images/vector-83.svg" class="vector-83" alt="vector" />
<div class="group-84">
<img src="images/vector-85.svg" class="vector-85" alt="vector" />
<img src="images/vector-86.svg" class="vector-86" alt="vector" />
<img src="images/vector-87.svg" class="vector-87" alt="vector" />
</div>
<img src="images/vector-88.svg" class="vector-88" alt="vector" />
<div class="group-89">
<img src="images/vector-90.svg" class="vector-90" alt="vector" />
</div>
<img src="images/vector-stroke-91.svg" class="vector-stroke-91" alt="vector-stroke" />
<img src="images/vector-92.svg" class="vector-92" alt="vector" />
<img src="images/vector-stroke-93.svg" class="vector-stroke-93" alt="vector-stroke" />
<img src="images/vector-94.svg" class="vector-94" alt="vector" />
<img src="images/vector-stroke-95.svg" class="vector-stroke-95" alt="vector-stroke" />
<img src="images/vector-96.svg" class="vector-96" alt="vector" />
<img src="images/vector-stroke-97.svg" class="vector-stroke-97" alt="vector-stroke" />
<img src="images/vector-98.svg" class="vector-98" alt="vector" />
<img src="images/vector-stroke-99.svg" class="vector-stroke-99" alt="vector-stroke" />
<img src="images/vector-100.svg" class="vector-100" alt="vector" />
<img src="images/vector-stroke-101.svg" class="vector-stroke-101" alt="vector-stroke" />
<img src="images/vector-102.svg" class="vector-102" alt="vector" />
<img src="images/vector-103.svg" class="vector-103" alt="vector" />
<img src="images/vector-stroke-104.svg" class="vector-stroke-104" alt="vector-stroke" />
<img src="images/vector-105.svg" class="vector-105" alt="vector" />
<img src="images/vector-106.svg" class="vector-106" alt="vector" />
<img src="images/vector-stroke-107.svg" class="vector-stroke-107" alt="vector-stroke" />
<img src="images/vector-108.svg" class="vector-108" alt="vector" />
<img src="images/vector-109.svg" class="vector-109" alt="vector" />
<img src="images/vector-stroke-110.svg" class="vector-stroke-110" alt="vector-stroke" />
<img src="images/vector-111.svg" class="vector-111" alt="vector" />
<img src="images/vector-112.svg" class="vector-112" alt="vector" />
<img src="images/vector-stroke-113.svg" class="vector-stroke-113" alt="vector-stroke" />
<img src="images/vector-stroke-114.svg" class="vector-stroke-114" alt="vector-stroke" />
<img src="images/vector-stroke-115.svg" class="vector-stroke-115" alt="vector-stroke" />
<img src="images/vector-stroke-116.svg" class="vector-stroke-116" alt="vector-stroke" />
<img src="images/vector-117.svg" class="vector-117" alt="vector" />
<img src="images/vector-stroke-118.svg" class="vector-stroke-118" alt="vector-stroke" />
<img src="images/vector-stroke-119.svg" class="vector-stroke-119" alt="vector-stroke" />
<img src="images/vector-stroke-120.svg" class="vector-stroke-120" alt="vector-stroke" />
<img src="images/vector-stroke-121.svg" class="vector-stroke-121" alt="vector-stroke" />
<img src="images/vector-stroke-122.svg" class="vector-stroke-122" alt="vector-stroke" />
<img src="images/vector-123.svg" class="vector-123" alt="vector" />
<img src="images/vector-124.svg" class="vector-124" alt="vector" />
<img src="images/vector-125.svg" class="vector-125" alt="vector" />
<img src="images/vector-stroke-126.svg" class="vector-stroke-126" alt="vector-stroke" />
<img src="images/vector-stroke-127.svg" class="vector-stroke-127" alt="vector-stroke" />
<img src="images/vector-stroke-128.svg" class="vector-stroke-128" alt="vector-stroke" />
<img src="images/vector-stroke-129.svg" class="vector-stroke-129" alt="vector-stroke" />
<img src="images/vector-stroke-130.svg" class="vector-stroke-130" alt="vector-stroke" />
<img src="images/vector-stroke-131.svg" class="vector-stroke-131" alt="vector-stroke" />
<img src="images/vector-stroke-132.svg" class="vector-stroke-132" alt="vector-stroke" />
<img src="images/vector-133.svg" class="vector-133" alt="vector" />
<img src="images/vector-134.svg" class="vector-134" alt="vector" />
<img src="images/vector-135.svg" class="vector-135" alt="vector" />
<img src="images/vector-stroke-136.svg" class="vector-stroke-136" alt="vector-stroke" />
<img src="images/vector-stroke-137.svg" class="vector-stroke-137" alt="vector-stroke" />
<img src="images/vector-stroke-138.svg" class="vector-stroke-138" alt="vector-stroke" />
<img src="images/vector-stroke-139.svg" class="vector-stroke-139" alt="vector-stroke" />
<img src="images/vector-stroke-140.svg" class="vector-stroke-140" alt="vector-stroke" />
<img src="images/vector-stroke-141.svg" class="vector-stroke-141" alt="vector-stroke" />
<img src="images/vector-stroke-142.svg" class="vector-stroke-142" alt="vector-stroke" />
<img src="images/vector-stroke-143.svg" class="vector-stroke-143" alt="vector-stroke" />
<img src="images/vector-stroke-144.svg" class="vector-stroke-144" alt="vector-stroke" />
</div>
</div>
<div class="login-container-145">
<div class="rectangle-3-146"></div>
<p class="text-147"><span class="text-rgb-53-91-62">Artificial Intelligence giving you travel recommendations</span></p>
<div class="frame-13-148">
<div class="logo-149">
<img src="images/union-150.svg" class="union-150" alt="union" />
</div>
<p class="text-151"><span class="text-rgb-53-91-62">Travalizer</span></p>
</div>
<p class="text-152"><span class="text-rgb-88-116-94">Welcome Back, Please login to your account</span></p>
<div class="login-form-153">
<div class="email-154">
<p class="text-155"><span class="text-rgb-47-61-76">Email</span></p>
<div class="frame-2-156">
<p class="text-157"><span class="text-rgb-53-91-62">robert.langster@gmail.com</span></p>
</div>
</div>
<div class="password-158">
<p class="text-159"><span class="text-rgb-53-91-62">Password</span></p>
<div class="frame-2-160">
<div class="frame-14-161">
<img src="images/ellipse-2-162.svg" class="ellipse-2-162" alt="ellipse-2" />
<img src="images/ellipse-3-163.svg" class="ellipse-3-163" alt="ellipse-3" />
<img src="images/ellipse-4-164.svg" class="ellipse-4-164" alt="ellipse-4" />
<img src="images/ellipse-5-165.svg" class="ellipse-5-165" alt="ellipse-5" />
<img src="images/ellipse-6-166.svg" class="ellipse-6-166" alt="ellipse-6" />
<img src="images/ellipse-7-167.svg" class="ellipse-7-167" alt="ellipse-7" />
<img src="images/ellipse-8-168.svg" class="ellipse-8-168" alt="ellipse-8" />
<img src="images/ellipse-9-169.svg" class="ellipse-9-169" alt="ellipse-9" />
</div>
<div class="eye-off-170">
<img src="images/vector-171.svg" class="vector-171" alt="vector" />
</div>
</div>
</div>
<div class="extra-172">
<div class="remember-173">
<div class="check-box-default-174">
<img src="images/vector-175.svg" class="vector-175" alt="vector" />
</div>
<p class="text-176"><span class="text-rgb-53-91-62">Remember me</span></p>
</div>
<p class="text-177"><span class="text-rgb-53-91-62">Forgot password?</span></p>
</div>
<div class="frame-16-178">
<div class="button-login-179">
<p class="text-180"><span class="text-white">Login</span></p>
</div>
<div class="button-login-181">
<p class="text-182"><span class="text-rgb-2-150-100">Sign Up</span></p>
</div>
</div>
</div>
<div class="frame-17-183">
<p class="text-184"><span class="text-rgb-88-116-94">Or, login with</span></p>
<p class="text-185"><span class="text-rgb-53-91-62">Facebook</span></p>
<p class="text-186"><span class="text-rgb-53-91-62">Linked In</span></p>
<p class="text-187"><span class="text-rgb-53-91-62">Google</span></p>
</div>
</div>
</div>
</div>
<script>
    const roleSelect = document.querySelector('select[name="role"]');
    const avatarImg = document.getElementById('user-avatar');

    roleSelect.addEventListener('change', function () {
      avatarImg.src = this.value === 'admin' ? 'admin_person.png' : 'user_person.png';
    });
  </script>

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
