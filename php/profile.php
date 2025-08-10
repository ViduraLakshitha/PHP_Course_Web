<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}
$username = $_SESSION['username'];
$users_file = 'data/users.txt';
$userData = null;
if (file_exists($users_file)) {
    $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($user, $hash, $email) = explode('|', $line);
        if (rtrim(strtolower($user), "\r\n\t ") === rtrim(strtolower($username), "\r\n\t ")) {
            $userData = ['username' => $user, 'email' => $email, 'hash' => $hash];
            break;
        }
    }
}
if (!$userData) {
    echo '<div class="error-msg">User not found.</div>';
    exit();
}
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : $userData['email'];
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $update = false;
    // Validate email
    if ($new_email === '' || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $new_hash = $userData['hash'];
        if ($new_password !== '') {
            if (strlen($new_password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } else {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update = true;
            }
        }
        // Only update if email changed or password changed
        if ($new_email !== $userData['email'] || $update) {
            $new_lines = [];
            foreach ($lines as $line) {
                list($user, $hash, $email) = explode('|', $line);
                if (rtrim(strtolower($user), "\r\n\t ") === rtrim(strtolower($username), "\r\n\t ")) {
                    $new_lines[] = "$user|$new_hash|$new_email";
                } else {
                    $new_lines[] = $line;
                }
            }
            $result = @file_put_contents($users_file, implode("\n", $new_lines) . "\n");
            if ($result === false) {
                $error = 'Failed to write to users.txt. Check file permissions.';
            } else {
                $userData['email'] = $new_email;
                $success = true;
            }
        }
    }
}
?>
<div class="centered-card">
  <img src="assets/60111.jpg" alt="Profile Photo" style="display:block;margin:0 auto 1.5rem auto;width:120px;height:120px;object-fit:cover;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
  <h2 style="text-align:center;">My Profile</h2>
  <form method="post" style="display:flex;flex-direction:column;gap:1rem;">
    <div style="margin-bottom:1.5rem;">
      <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
      <label for="email"><strong>Email:</strong></label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
    </div>
    <hr>
    <h3 style="margin-top:1.5rem;">Change Password</h3>
    <?php if ($success): ?>
      <div style="color:green;">Profile updated successfully!</div>
    <?php elseif ($error): ?>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
    <input type="submit" value="Update Profile" class="cta-btn">
  </form>
  <form action="php/logout.php" method="post" style="margin-top:2rem;text-align:center;">
    <button type="submit" class="cta-btn" style="background:#d32f2f;color:#fff;">Logout</button>
  </form>
</div> 