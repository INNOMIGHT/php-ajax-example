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
$listingController = new ListingController($db); // Initialize ListingController

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Attempt to log in
        $loggedIn = $authController->login($_POST['username'], $_POST['password']);
        if (!$loggedIn) {
            // Redirect back to login page with error parameter
            header("Location: /tasks/authentication/login.php?error=1");
            exit();
        }
    } elseif (isset($_POST['register'])) {
        // Register new user
        $registrationResult = $authController->register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['phone']); 
        if ($registrationResult === true) {
            // Redirect back to register page with success parameter
            header("Location: /tasks/authentication/login.php?registered=1");
            exit();
        } else {
            // Redirect back to register page with error parameter based on error type
            header("Location: /tasks/authentication/register.php?error=$registrationResult");
            exit();
        }
    } elseif (isset($_POST['logout'])) {
        // Register new user
        $authController->logout(); 
    }

    // elseif (isset($_POST['add_listing'])) {
    //     echo "Add Listing Request Initiated!";
    //     $title = $_POST['title'];
    //     $description = $_POST['description'];
    //     $image = $_POST['image'];
    //     $email = $_POST['email'];
    //     $phoneNumber = $_POST['phoneNumber'];
    
    //     // Create a new Listing instance
    //     $listing = new Listing($db->getPdo(), null, $title, $description, $image, $email, $phoneNumber);
    
    //     // Add the listing using the ListingController
    //     $listingController->addListing($listing);
    
    //     // Return success message
    //     echo "Listing added successfully!";
    // }

    // elseif (isset($_POST['edit_listing'])) {
    //     // Edit listing
    //     if(isset($_POST['id']) && !empty($_POST['id'])) {
    
    //         $id = $_POST['id'];
    //         $title = $_POST['title'];
    //         $description = $_POST['description'];
    //         $image = $_POST['image']; // You may need to handle file uploads separately
    //         $email = $_POST['email'];
    //         $phoneNumber = $_POST['phoneNumber'];
    
    //         // Edit the listing using the ListingController
    //         $success = $listingController->editListing($id, $title, $description, $image, $email, $phoneNumber);
    
    //         if ($success) {
    //             // Redirect to a confirmation page or home page
    //             header("Location: /tasks/authentication/home.php");
    //             exit();
    //         } else {
    //             // Handle edit failure, maybe redirect to an error page
    //             echo "Failed to edit listing.";
    //             exit();
    //         }
    //     }
    // }
    

    // elseif (isset($_POST['delete_listing'])) {
    //     // Delete listing
    //     if(isset($_POST['id']) && !empty($_POST['id'])) {
    //         $id = $_POST['id'];
    //         // Delete the listing using the ListingController
    //         $listingController->deleteListing($id);

    //         // Redirect to a confirmation page or home page
    //         header("Location: /tasks/authentication/home.php");
    //         exit();
    //     }
    // }
}
?>
