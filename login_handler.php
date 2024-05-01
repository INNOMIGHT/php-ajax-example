<?php
require_once 'controllers/AuthController.php';
require_once 'config/database.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);

$authController = new AuthController($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Attempt to log in
    $loggedIn = $authController->login($_POST['username'], $_POST['password']);
    if (!$loggedIn) {
        // Return a JSON response indicating unsuccessful login
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User is not logged in.']);
        exit();
    }
    else {
        // Redirect to the home page upon successful login
        header("Location: /tasks/authentication/home.php");
        exit();
    }
}

// Log form data for troubleshooting
error_log("Login Form Data: " . print_r($_POST, true));
?>
