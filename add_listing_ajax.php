<?php
require_once 'config/database.php';
require_once 'controllers/ListingController.php';
require_once 'models/Listing.php';
require_once 'jwt_helper.php';

session_start();

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db); // Initialize ListingController

$jwt = $_SESSION['jwt'];
$decoded = JwtHelper::decode($jwt);
$user_id = $decoded->user_id;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process AJAX request to add a listing
    $title = $_POST['title'];
    $description = $_POST['description'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $file = $_FILES['imageFile'];


    $result = $listingController->addListing($title, $description, $email, $phoneNumber, $file, $user_id);

  
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add listing.']);
    }
}
?>
