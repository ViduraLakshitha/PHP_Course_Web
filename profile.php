<?php error_reporting(E_ALL); ini_set('display_errors', 1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - course_web</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="navbar">
    <a href="index.html" class="logo">course_web</a>
    <div class="search-bar">
      <input type="text" placeholder="Search courses...">
    </div>
    <div class="nav-links">
      <a href="index.html">Home</a>
      <a href="courses.html">Courses</a>
      <a href="mycourses.html">My Courses</a>
      <a href="/web%20assignmen/profile.php">Profile</a>
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
      <a href="contact.html">Contact</a>
    </div>
  </div>
  <div class="centered-container">
    <?php include 'php/profile.php'; ?>
  </div>
  <footer>
    <p>&copy; 2024 course_web. All rights reserved.</p>
  </footer>
  <script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('php/check_user.php')
    .then(response => response.json())
    .then(data => {
      if (data.loggedIn) {
        document.querySelectorAll('.nav-links a[href="login.html"], .nav-links a[href="register.html"]').forEach(el => el.style.display = 'none');
      }
    });
});
</script>
</body>
</html> 