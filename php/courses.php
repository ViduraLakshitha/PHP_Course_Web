<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo '<tr><td colspan="3">Please <a href="login.html">login</a> to enroll in courses.</td></tr>';
    exit();
}

$courses_file = '../data/courses.txt';
if (!file_exists($courses_file)) {
    echo '<tr><td colspan="3">No courses available.</td></tr>';
    exit();
}

$username = $_SESSION['username'];
$courses = file($courses_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($courses as $course) {
    $parts = explode('|', $course);
    $id = $parts[0];
    $name = $parts[1];
    $image = isset($parts[2]) ? $parts[2] : '';
    echo "<tr>";
    echo "<td>" . htmlspecialchars($id) . "</td>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td data-image='" . htmlspecialchars($image) . "'>";
    echo "<form method='post' action='php/enroll.php' style='margin:0;'><input type='hidden' name='course_id' value='" . htmlspecialchars($id) . "'><input type='submit' value='Enroll'></form>";
    echo "</td>";
    echo "</tr>";
} 