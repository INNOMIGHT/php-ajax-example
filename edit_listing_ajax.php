<?php

require_once 'config/database.php';
require_once 'controllers/ListingController.php';
require_once 'jwt_helper.php';


// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';


$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db); 

session_Start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the edit_listing parameter is set
    
    // Edit listing
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];

        // Check if a new image file is uploaded
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            echo "Uploaded";
        } else {
            // If no new image is uploaded, use the existing image URL
            $image = $_FILES['image'];
            echo "Not Uploaded";
            echo $_FILES['image']['error'];
            
        }
        $jwt = $_SESSION['jwt'];
        $decoded = JwtHelper::decode($jwt);
        $user_id = $decoded->user_id;


        $success = $listingController->editListing($id, $title, $description, $image, $email, $phoneNumber, $user_id);

        if ($success) {
            echo "Listing edited successfully!";
        } else {
            echo "Failed to edit listing.";
        }
    } else {
        // Return error message if ID is not provided
        echo "Listing ID is missing.";
    }
}

?>
