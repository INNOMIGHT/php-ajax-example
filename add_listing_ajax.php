<?php
require_once 'config/database.php';
require_once 'controllers/ListingController.php';
require_once 'models/Listing.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'task-authentication';

$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db); // Initialize ListingController

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Process AJAX request to add a listing
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
    
        // Create a new Listing instance
        $listing = new Listing($db->getPdo(), null, $title, $description, $image, $email, $phoneNumber);
    
        // Add the listing using the ListingController
        $listingController->addListing($listing);
    
        // Return response indicating success or failure
        echo json_encode(['success' => true]);
        exit();
    

    // Handle other AJAX requests (edit_listing, delete_listing) similarly
}
?>
