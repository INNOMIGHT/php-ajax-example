<?php
// Check if the request is an AJAX request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    // If not an AJAX request, return a 403 Forbidden response
    http_response_code(403);
    exit();
}

// Validate input data
$errors = [];

// Validate username
$username = $_POST['username'] ?? '';
if (empty($username)) {
    $errors['username'] = 'Username is required.';
} elseif (strlen($username) < 4) {
    $errors['username'] = 'Username must be at least 4 characters long.';
}

//Validate password
$password = $_POST['password'] ?? '';
if (empty($password)) {
    $errors['password'] = 'Password is required.';
} elseif (strlen($password) < 10 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
    $errors['password'] = 'Password must be at least 10 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one symbol.';
}

// Validate email
$email = $_POST['email'] ?? '';
if (empty($email)) {
    $errors['email'] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format.';
}

$address = $_POST['address'] ?? '';
if (empty($address)) {
    $errors['address'] = 'Address is required.';
} elseif (strlen($address) < 10) {
    $errors['address'] = 'Address must be at least 10 characters long.';
}

// Validate bio
$bio = $_POST['bio'] ?? '';
if (empty($bio)) {
    $errors['bio'] = 'Bio is required.';
} elseif (strlen($bio) < 10) {
    $errors['bio'] = 'Bio must be at least 10 characters long.';
}

// Validate profile photo
if (!isset($_FILES['profilePhoto']) || $_FILES['profilePhoto']['error'] !== UPLOAD_ERR_OK) {
    $errors['profilePhoto'] = 'Profile photo is required and must be uploaded.';
} else {
    $maxSize = 1 * 1024; // 2 MB
    $imageInfo = getimagesize($_FILES['profilePhoto']['tmp_name']);
    if ($_FILES['profilePhoto']['size'] > $maxSize || !$imageInfo || $imageInfo[0] !== $imageInfo[1]) {
        $errors['profilePhoto'] = 'Profile photo must be a squared dimension (length and breadth should be same) image and within 2 MB size.';
    }
}

// Output validation results
echo json_encode(['errors' => $errors]);
