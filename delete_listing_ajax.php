<?php
require_once 'config/database.php';
require_once 'controllers/ListingController.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        // Delete the listing using the ListingController
        $listingController->deleteListing($id);
        echo "Listing deleted successfully!";
    } else {
        echo "Listing ID is missing.";
    }
}
?>
