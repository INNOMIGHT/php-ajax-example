<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <?php
                            // Check if registration success message needs to be shown
                            if (isset($_GET['registered']) && $_GET['registered'] == 1) {
                                echo '<div class="alert alert-success" role="alert">Registration successful! You can now login.</div>';
                            }
                        ?>
                        <div id="error-msg" class="alert alert-danger" role="alert" style="display: none;"></div>
                        <form id="login-form">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
                <p>Don't have an account? <a href="/tasks/authentication/register.php">Register Here!</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN (optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>  
    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(event) {
                event.preventDefault(); 
                var formData = new FormData($(this)[0]); 

                // Send AJAX request
                $.ajax({
                    url: '/tasks/authentication/login_handler.php', 
                    type: 'POST',
                    data: formData,
                    processData: false,  
                    contentType: false,  
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        console.log("success request");
                        console.log(formData);
                        window.location.href = "listings.php";
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        if(xhr.status == 401) {
                            $('#error-msg').text('Invalid Credentials. Please try again.');
                            $('#error-msg').show();
                        } else {
                            console.error(xhr.responseText);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
