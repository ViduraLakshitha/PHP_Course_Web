<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.html');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $username = $_SESSION['username'];
    $course_id = $_POST['course_id'];
    $enrollments_file = '../data/enrollments.txt';
    $already_enrolled = false;
    if (file_exists($enrollments_file)) {
        $lines = file($enrollments_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($user, $cid) = explode('|', $line);
            if ($user === $username && $cid === $course_id) {
                $already_enrolled = true;
                break;
            }
        }
    }
    if (!$already_enrolled) {
        file_put_contents($enrollments_file, "$username|$course_id\n", FILE_APPEND);
    }
}
header('Location: ../courses.html');
exit(); 