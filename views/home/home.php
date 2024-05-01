<?php

// Require the ListingController using __DIR__
require_once dirname(__DIR__, 2) . '/controllers/ListingController.php';
require_once dirname(__DIR__, 2) . '/controllers/AuthController.php';

require_once dirname(__DIR__, 2) . '/config/database.php';

require_once dirname(__DIR__, 2) . '/jwt_helper.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
session_start();

// Check if the user is logged in
if (!isset($_SESSION['jwt'])) {
    // If not logged in, redirect to the login page
    header("Location: /tasks/authentication/login.php");
    exit();
}



// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);

$listingController = new ListingController($db); // Initialize ListingController
$authController = new AuthController($db);

$jwt = $_SESSION['jwt'];

// Decode JWT to retrieve user ID
$decoded = JwtHelper::decode($jwt);
// $user_id = $authController->validateJwt($jwt);
$user_id = $decoded->user_id;
$user = $authController->findUserById($user_id);

// Fetch the listings from the database using the ListingController
$listings = $listingController->getAllListings($user_id); // Assuming you have a method to fetch all listings



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    
    <style>
        .img-thumbnail {
            height: 120px;
            width: 120px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
    <a class="navbar-brand" href="/tasks/authentication/listings.php">Products Listing</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="add_listing.php"><i class="fas fa-plus"></i> Add Listing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="myprofile.php"><i class="fas fa-user"></i> My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tasks/authentication/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Listings</div>
                <div class="card-body">
                    <div class="list-group">
                        <?php if (!empty($listings)): ?>
                            <?php foreach ($listings as $listing): ?>
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col">
                                        <a href="/tasks/authentication/listing_details/<?= $listing->getId() ?>">
                                            <h5 class="mb-1"><?= $listing->getTitle() ?></h5>
                            </a>
                                            <p class="mb-1"><?= $listing->getDescription() ?></p>
                                            <p class="mb-1">Listing By: <a href="myprofile.php"><?= $user->getUsername() ?></a></p>
                                            <p class="mb-1">Contact: <?= $listing->getPhoneNumber() ?></p>
                                        </div>
                                        <div class="col-auto">
                                            <img src="<?= $listing->getImagePath() ?>" class="img-thumbnail" alt="Listing Image">
                                        </div>
                                        <div class="col-auto">
                                            <!-- Edit and delete buttons -->
                                            <?php 
                                            // Generate the URL parameter with ID
                                            $listingId = $listing->getId();
                                            ?>
                                            <a href="edit_listing/<?= $listing->getId() ?>" class="btn btn-sm btn-primary mr-2"><i class="fas fa-edit"></i> Edit</a><br/><br/>
                                            <form class="delete-listing-form" method="POST">
                                                <input type="hidden" name="id" value="<?= $listing->getId() ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" name="delete_listing" onclick="return confirm('Are you sure you want to delete this listing?')"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No listings available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('.delete-listing-form').submit(function(event) {
        event.preventDefault(); 
        var formData = $(this).serialize(); 
        var form = $(this); // Reference to the current form

        $.ajax({
            url: '/tasks/authentication/delete_listing_ajax.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                console.log("Listing Deleted!");
                // Remove the deleted listing from the DOM
                form.closest('.list-group-item').remove(); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                
            }
        });
    });
});



    
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome CDN for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
