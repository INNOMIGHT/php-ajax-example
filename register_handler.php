<?php

require_once 'controllers/AuthController.php';
require_once 'config/database.php';
require_once 'controllers/ListingController.php';
require_once 'models/Listing.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);

$authController = new AuthController($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'] ? $_POST['address'] : null;
    $bio = $_POST['bio'] ? $_POST['bio'] : null;
    $profilePhoto = $_FILES['profilePhoto'];
    // Handle profile photo upload
    

    // Call the register method of AuthController
    $result = $authController->register($username, $password, $email, $address, $bio, $profilePhoto);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add User.']);
    }
}

