<?php

require_once 'config/database.php';
require_once 'controllers/ListingController.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'task-authentication';

$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db); // Initialize ListingController

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the edit_listing parameter is set
    
        // Edit listing
        if(isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $image = $_POST['image']; // You may need to handle file uploads separately
            $email = $_POST['email'];
            $phoneNumber = $_POST['phoneNumber'];
    
            // Edit the listing using the ListingController
            $success = $listingController->editListing($id, $title, $description, $image, $email, $phoneNumber);
    
            // Check if the edit was successful
            if ($success) {
                // Return success message
                echo "Listing edited successfully!";
            } else {
                // Return error message
                echo "Failed to edit listing.";
            }
        } else {
            // Return error message if ID is not provided
            echo "Listing ID is missing.";
        }
    }

?>