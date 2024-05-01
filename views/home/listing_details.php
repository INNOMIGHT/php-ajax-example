<?php
// Require the ListingController and Listing models
require_once dirname(__DIR__, 2) . '/controllers/ListingController.php';
require_once dirname(__DIR__, 2) . '/models/Listing.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);
$listingController = new ListingController($db);

// Check if the product ID is provided in the URL
if (isset($_GET['id'])) {
    $listingId = $_GET['id'];
    
    // Find the product by ID
    $listing = $listingController->getListing($listingId);


    // Check if the listing exists
    if ($listing) {
        // Product details found, display them
        $title = $listing->getTitle();
        $description = $listing->getDescription();
        $imagePath = $listing->getImagePath();
        $email = $listing->getEmail();
        $phoneNumber = $listing->getPhoneNumber();
    } else {
        // Product not found, display error message
        $error = "Product not found.";
    }
} else {
    // ID not provided in URL, display error message
    $error = "Product ID not provided.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            width: 100%;
            height: auto;
        }

        .card {
            border: none;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
    <a class="navbar-brand" href="/tasks/authentication/listings.php">Products Listing</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/tasks/authentication/add_listing.php"><i class="fas fa-plus"></i> Add Listing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tasks/authentication/myprofile.php"><i class="fas fa-user"></i> My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tasks/authentication/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>
    <div class="container mt-5">
    <a href="/tasks/authentication/listings.php"><p><- Go Back to Listings</p></a>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="/tasks/authentication/<?= $listing->getImagePath() ?>" class="card-img-top product-img"
                        alt="Listing Image">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="card-title"><?php echo $title; ?></h2>
                    </div>
                    <div class="card-body">
                        <h4>Description</h4>
                        <p class="card-text"><?php echo $description; ?></p>
                        <h4>Contact Details</h4>
                        <p class="card-text">Email: <?php echo $email; ?></p>
                        <p class="card-text">Phone Number: <?php echo $phoneNumber; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>Copyright &copy; 2024. All rights reserved.</p>
        </div>
    </footer>
    <!-- Bootstrap JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome CDN for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script></body>

</html>



