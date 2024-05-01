<?php
require_once dirname(__DIR__, 2) . '/controllers/AuthController.php';
require_once dirname(__DIR__, 2) . '/config/database.php';

require_once dirname(__DIR__, 2) . '/jwt_helper.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['jwt'])) {
    // Redirect to login page if not logged in
    header('Location: /tasks/authentication/login.php');
    exit();
}

// Get user ID from session
$jwt = $_SESSION['jwt'];
$decoded = JwtHelper::decode($jwt);
// $user_id = $authController->validateJwt($jwt);
$user_id = $decoded->user_id;


// Initialize AuthController
// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);

$authController = new AuthController($db);

// Get user details
$user = $authController->findUserById($user_id);

// Check if user exists
if (!$user) {
    // Redirect or handle error if user not found
    header('Location: /tasks/authentication/error.php?error=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .img-thumbnail {
            height: 120px;
            width: 150px;
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">User Profile</div>
                    <div class="card-body">
                    <p><strong>Profile Photo:</strong></p>
                    <img src="<?= $user->getProfilePhoto() ?>" class="img-thumbnail mb-3" alt="Listing Image">

                        <p><strong>Username:</strong> <?php echo $user->getUsername(); ?></p>
                        <p><strong>Email:</strong> <?php echo $user->getEmail(); ?></p>
                        <p><strong>Bio:</strong> <?php echo $user->getBio(); ?></p>
                        <p><strong>Address:</strong> <?php echo $user->getAddress(); ?></p>

                        <!-- Add more user details as needed -->

                        <!-- Change Password button -->
                        <a href="/tasks/authentication/change_password.php" class="btn btn-primary">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome CDN for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
