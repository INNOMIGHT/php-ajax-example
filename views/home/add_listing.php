<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['jwt'])) {
    // If not logged in, redirect to the login page
    header("Location: /tasks/authentication/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listing</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
    <a class="navbar-brand" href="/tasks/authentication/home.php">Products Listings</a>
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
                <div class="card-header">Add Listing</div>
                <div class="card-body">
                    <form id="add_listing_form" method="POST">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL:</label>
                            <input type="text" class="form-control" id="image" name="image">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_listing">Add Listing</button>
                    </form>
                </div>
            </div>
            <br />
            <p>Go Back to <a href="home.php">Products Listing</a></p>
        </div>
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add_listing_form').submit(function(event) {
            event.preventDefault(); 
            var formData = $(this).serialize(); // Serialize form data

            // Send AJAX request
            $.ajax({
                url: '/tasks/authentication/add_listing_ajax.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    console.log("success request");
                    console.log(formData);
                    window.location.href = "home.php";
                },
                error: function(xhr, status, error) {
                    
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
