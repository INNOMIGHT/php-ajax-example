<?php
// change_password_handler.php
session_start();

require_once 'controllers/AuthController.php';
require_once 'config/database.php';
require_once 'jwt_helper.php';

// Initialize the Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'user-listings';

$db = new Database($host, $user, $password, $dbname);

$authController = new AuthController($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['jwt'])) {
        echo json_encode(['success' => false, 'message' => 'User is not logged in.']);
        exit();
    }

    // Get the user ID from the session
    $jwt = $_SESSION['jwt'];
    $decoded = JwtHelper::decode($jwt);
    $userId = $decoded->user_id;
    $user = $authController->findUserById($userId);

    if (!$decoded) {
        echo json_encode(['success' => false, 'message' => 'Invalid JWT.']);
        exit();
    }

    // Get the current password from the form
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate password fields
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All password fields are required.']);
        exit();
    }

    // Verify the current password
    if (!password_verify($currentPassword, $user->getPassword())) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit();
    }

    // Validate new password format
    if (strlen($newPassword) < 6 || !preg_match('/\d/', $newPassword) || !preg_match('/[^A-Za-z0-9]/', $newPassword)) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long and contain at least one number and one symbol.']);
        exit();
    }

    // Check if new password matches confirm password
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match.']);
        exit();
    }

    // Change the password
    $success = $authController->changePassword($userId, $newPassword);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to change password.']);
        exit();
    }
}
?>
