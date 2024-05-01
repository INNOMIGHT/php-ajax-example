<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

// require_once 'config/database.php';
// require_once 'models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../jwt_helper.php';


// require_once 'vendor/autoload.php'; // Include autoload.php from firebase/php-jwt library
// require_once 'vendor/jwt_helper.php';

use Firebase\JWT\JWT;

class AuthController {
    private $db;
    private $jwtSecret = 'vaibhav_sec_key';

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function index() {
        session_start();

        // Check if user is logged in
        if (isset($_SESSION['jwt'])) {
            // User is logged in, redirect to home page
            header('Location: /tasks/authentication/views/home/home.php');
            exit();
        } else {
            // User is not logged in, redirect to login page
            header('Location: /tasks/authentication/views/auth/login.php');
            exit();
        }
    }

    public function login($username, $password) {
        session_start();
        // Validate username and password
        if (empty($username) || empty($password)) {
            return false; // Invalid username or password
        }

        // Check if user exists in the database
        $user = User::findByUsername($this->db->getPdo(), $username);
        if (!$user) {
            header("Location: /tasks/authentication/login.php?error=1"); // User not found
            return false;
        }

        // Verify password
        if (!password_verify($password, $user->getPassword())) {
            header("Location: /tasks/authentication/login.php?error=1"); // Incorrect password
            return false;
        }

        // User authenticated, generate JWT token
        $jwt = $this->generateJwt($user->getId(), $user->getUsername());

        $_SESSION['jwt'] = $jwt;
        header('LOCATION: /tasks/authentication/home.php');
        return true;
        
    }

    public function generateJwt($userId, $username) {
        $payload = [
            'user_id' => $userId,
            'username' => $username,
            'iat' => time(), // Issued at time
            'exp' => time() + (60 * 60 * 24) // Expiration time (1 day)
        ];
        $jwt = JwtHelper::encode($payload);
        return $jwt;
    }

    public function validateJwt($jwt) {
        try {
            $algorithm = 'HS256';
            $decoded = JwtHelper::decode($jwt);
            return $decoded->user_id;
        } catch (Exception $e) {
            return false; // JWT validation failed
        }
    }

    public function register($username, $password, $email, $address, $bio, $profilePhoto) {
        // Validate input data
        if (empty($username) || empty($password) || empty($email)) {
            header("Location: /tasks/authentication/register.php?error=4"); // Empty fields
            exit();
        }
    
        // Check username length
        if (strlen($username) < 4) {
            header("Location: /tasks/authentication/register.php?error=6"); // Username must be at least 4 characters long
            exit();
        }
    
        // Check password length and numeric character
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
            header("Location: /tasks/authentication/register.php?error=8"); // Password must be at least 8 characters long and contain at least one letter, one number, and one symbol
            exit();
        }
    
        // Check if username or email already exist
        if (User::findByUsername($this->db->getPdo(), $username)) {
            header("Location: /tasks/authentication/register.php?error=1"); // Username already exists
            exit();
        }
        if (User::findByEmail($this->db->getPdo(), $email)) {
            header("Location: /tasks/authentication/register.php?error=2"); // Email already exists
            exit();
        }
    
        // Create a new User object
        $user = new User($this->db->getPdo(), $username, $password, $email, $address, $bio, $profilePhoto);
        
        if ($profilePhoto['error'] === UPLOAD_ERR_OK) {

            // Upload image file
            if ($user->uploadImage($profilePhoto)) {
                return $user->save();
            } else {
                // Handle image upload error
                return false;
            }
        } else {
            // Handle file upload error
            return false;
        }
    
        // Save the user to the database
        if ($user->save()) {
            header("Location: /tasks/authentication/login.php?registered=1"); // Registration successful
            exit();
        } else {
            header("Location: /tasks/authentication/register.php?error=5"); // Registration failed
            exit();
        }
    }

    public function findUserById($id){
        $user = User::findById($this->db->getPdo(), $id);
        return $user;
    }
    
    public function changePassword($userId, $newPassword) {
        try {
            // Hash the new password before saving it
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
            // Update the user's password in the database
            $stmt = $this->db->getPdo()->prepare("UPDATE `user` SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);
    
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("LOCATION: /tasks/authentication/login.php");
        exit();
    }
}
?>
