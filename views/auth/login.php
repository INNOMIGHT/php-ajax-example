<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['jwt'])) {
    // If not logged in, redirect to the login page
    header("Location: /tasks/authentication/home.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : null;
$registered = isset($_GET['registered']) ? $_GET['registered'] : null;
// Display error messages based on error type
$errorMsg = '';
$registeredMsg = '';

if($registered==1){
    $registeredMsg = 'User Successfully Registered!';
}
elseif($error==1){
    $errorMsg = 'Login Failed! Please Try Again.';
}
?>

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
                    <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $errorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($registeredMsg)): ?>
                            <div class="alert alert-success" role="success">
                                <?php echo $registeredMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="/tasks/authentication/index.php" method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </form>
                        
                    </div>
                    
                </div>
                <p>Don't have an account? <a href="/tasks/authentication/register.php">Register Here!</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN (optional) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
