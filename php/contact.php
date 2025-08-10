<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $contacts_file = '../data/contacts.txt';
    if ($name === '' || $email === '' || $message === '') {
        header('Location: ../contact.html?error=empty');
        exit();
    }
    $timestamp = date('Y-m-d H:i:s');
    $entry = "$name|$email|$message|$timestamp\n";
    file_put_contents($contacts_file, $entry, FILE_APPEND);
    header('Location: ../contact.html?success=1');
    exit();
} else {
    header('Location: ../contact.html');
    exit();
} 