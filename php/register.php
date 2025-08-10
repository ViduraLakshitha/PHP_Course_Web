<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $users_file = '../data/users.txt';
    if ($username === '' || $password === '' || $email === '') {
        header('Location: ../register.html?error=empty');
        exit();
    }
    // Check for duplicate username or email
    if (file_exists($users_file)) {
        $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($user, $hash, $mail) = explode('|', $line);
            if ($user === $username) {
                header('Location: ../register.html?error=exists');
                exit();
            }
            if (strtolower($mail) === strtolower($email)) {
                header('Location: ../register.html?error=emailexists');
                exit();
            }
        }
    }
    // Hash the password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Save the new user
    file_put_contents($users_file, "$username|$hash|$email\n", FILE_APPEND);
    header('Location: ../login.html?registered=1');
    exit();
} else {
    header('Location: ../register.html');
    exit();
} 