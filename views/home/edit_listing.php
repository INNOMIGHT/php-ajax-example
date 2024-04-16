<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['jwt'])) {
    // If not logged in, redirect to the login page
    header("Location: /tasks/authentication/login.php");
    exit();
}

// Require the ListingController using __DIR__
require_once dirname(__DIR__, 2) . '/controllers/ListingController.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'task-authentication';

$db = new Database($host, $user, $password, $dbname);

$listingController = new ListingController($db); // Initialize ListingController

// Fetch the listing details based on the id parameter passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; 
    $listing = $listingController->getListing($id);
    if (!$listing) {
        // Redirect to error page or handle appropriately if listing not found
        header("Location: /tasks/authentication/error.php");
        exit();
    }
} else {
    // Redirect to error page or handle appropriately if id parameter not provided
    header("Location: /tasks/authentication/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
    <a class="navbar-brand" href="/tasks/authentication/home.php">Products Listing</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="add_listing.php"><i class="fas fa-plus"></i> Add Listing</a>
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
                <div class="card-header">Edit Listing</div>
                <div class="card-body">
                    <form id="edit_listing_form" method="POST">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= $listing->getTitle() ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?= $listing->getDescription() ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL:</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?= $listing->getImage() ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $listing->getEmail() ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" value="<?= $listing->getPhoneNumber() ?>" required>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-primary" name="edit_listing">Edit Listing</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#edit_listing_form').submit(function(event) {
            event.preventDefault(); 
            var formData = $(this).serialize(); 

            // Send AJAX request
            $.ajax({
                url: '/tasks/authentication/edit_listing_ajax.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    
                    console.log(response);
                    console.log("success request");
                    
                    window.location.href = '/tasks/authentication/home.php';
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                    
                }
            });

        });
    });
</script>
<!-- Bootstrap JS CDN (optional) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>