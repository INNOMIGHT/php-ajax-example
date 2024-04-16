// logout.php
<?php
require_once 'controllers/AuthController.php';
require_once 'config/database.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'task-authentication';

$db = new Database($host, $user, $password, $dbname);

$authController = new AuthController($db);

// Logout the user
$authController->logout();

// Redirect the user to the login page after logout
header('Location: /tasks/authentication/login.php');
exit();
?>