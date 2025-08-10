<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo '<tr><td colspan="2">Please <a href="login.html">login</a> to view your courses.</td></tr>';
    exit();
}
$username = $_SESSION['username'];
$enrollments_file = '../data/enrollments.txt';
$courses_file = '../data/courses.txt';
if (!file_exists($enrollments_file) || !file_exists($courses_file)) {
    echo '<tr><td colspan="2">No enrolled courses found.</td></tr>';
    exit();
}
// Build course id => [name, image] map
$course_map = [];
foreach (file($courses_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $parts = explode('|', $line);
    $cid = $parts[0];
    $cname = $parts[1];
    $cimg = isset($parts[2]) ? $parts[2] : '';
    $course_map[$cid] = [$cname, $cimg];
}
// Find user's enrollments
$rows = '';
foreach (file($enrollments_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    list($user, $cid) = explode('|', $line);
    if ($user === $username && isset($course_map[$cid])) {
        $rows .= '<tr><td>' . htmlspecialchars($cid) . '</td><td data-image="' . htmlspecialchars($course_map[$cid][1]) . '">' . htmlspecialchars($course_map[$cid][0]) . '</td></tr>';
    }
}
echo $rows ?: '<tr><td colspan="2">You are not enrolled in any courses.</td></tr>'; 