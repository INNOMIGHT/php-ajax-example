<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['jwt'])) {
    // Redirect to the home page
    header("Location: /tasks/authentication/home.php");
    exit();
}

// Check if the URL contains the 'error' query parameter
$error = isset($_GET['error']) ? $_GET['error'] : null;

// Display error messages based on error type
$errorMsg = '';
if ($error === '1') {
    $errorMsg = 'Username already exists. Please choose a different username.';
} elseif ($error === '2') {
    $errorMsg = 'Email already exists. Please use a different email address.';
} elseif ($error === '3') {
    $errorMsg = 'Phone number already exists. Please use a different phone number.';
} elseif ($error === '4') {
    $errorMsg = 'All fields are required. Please fill out all fields.';
} elseif ($error === '5') {
    $errorMsg = 'Registration failed. Please try again later.';
} elseif ($error === '6') {
    $errorMsg = 'Username must be at least 4 characters long.';
} elseif ($error === '7') {
    $errorMsg = 'Invalid phone number format. Please enter a 10-digit numeric phone number.';
} elseif ($error === '8') {
    $errorMsg = 'Password must be at least 6 characters long and contain at least one numeric and symbolic character.';
} elseif ($error === '9') {
    $errorMsg = 'Image must be under 2 mb and square (same length and breadth).';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $errorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form id="register-form" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <span id="username-error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span id="password-error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <span id="email-error" class="text-danger"></span>
                        </div>
                            
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                            <span id="address-error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                            <span id="bio-error" class="text-danger"></span>
                        </div>
                            <div class="form-group">
                                <label for="profilePhoto">Profile Photo</label>
                                <input type="file" class="form-control-file" id="profilePhoto" name="profilePhoto" required>
                                <span id="profilePhoto-error" class="text-danger"></span>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
                <p>Already have an account? <a href="/tasks/authentication/login.php">Login Here!</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS CDN (optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
   $(document).ready(function() {
    $('#register-form').submit(function(event) {
        event.preventDefault(); 
        var formData = new FormData($(this)[0]); 

        // Send AJAX request for validation
        $.ajax({
            url: '/tasks/authentication/register_validation.php', 
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            success: function(response) {
                // Handle validation results
                var errors = JSON.parse(response).errors;
                if (Object.keys(errors).length > 0) {
                    // Display validation errors
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value);
                    });
                } else {
                    // If no validation errors, proceed with form submission
                    $.ajax({
                        url: '/tasks/authentication/register_handler.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Handle success response
                            console.log(response);
                            window.location.href = "login.php?registered=1";
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
</body>
</html>
