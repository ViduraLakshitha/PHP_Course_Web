<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $users_file = '../data/users.txt';
    $found = false;
    if (file_exists($users_file)) {
        $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($user, $hash, $email) = explode('|', $line);
            if ($user === $username && password_verify($password, $hash)) {
                $_SESSION['username'] = $username;
                header('Location: ../index.html');
                exit();
            }
        }
    }
    // Login failed
    header('Location: ../login.html?error=1');
    exit();
} else {
    header('Location: ../login.html');
    exit();
} 