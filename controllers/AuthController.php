<?php

require_once 'config/database.php';
require_once 'models/User.php';

require_once 'vendor/autoload.php'; // Include autoload.php from firebase/php-jwt library
require_once 'vendor/jwt_helper.php';

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
            exit();
        }

        // Verify password
        if (!password_verify($password, $user->getPassword())) {
            header("Location: /tasks/authentication/login.php?error=1"); // Incorrect password
            exit();
        }

        // User authenticated, generate JWT token
        $jwt = $this->generateJwt($user->getId(), $user->getUsername());
        $_SESSION['jwt'] = $jwt;
        header('LOCATION: /tasks/authentication/home.php');
        exit();
    }

    public function generateJwt($userId, $username) {
        $payload = [
            'user_id' => $userId,
            'username' => $username,
            'iat' => time(), // Issued at time
            'exp' => time() + (60 * 60) // Expiration time (1 hour)
        ];
        $jwt = JwtHelper::encode($payload);
        return $jwt;
    }

    public function validateJwt($jwt) {
        try {
            $decoded = JWT::decode($jwt, $this->jwtSecret, ['HS256']);
            return $decoded->username;
        } catch (Exception $e) {
            return false; // JWT validation failed
        }
    }

    public function register($username, $password, $email, $phoneNumber) {
        // Validate input data
        if (empty($username) || empty($password) || empty($email) || empty($phoneNumber)) {
            header("Location: /tasks/authentication/register.php?error=4"); // Empty fields
            exit();
        }
    
        // Check username length
        if (strlen($username) < 4) {
            header("Location: /tasks/authentication/register.php?error=6"); // Username must be at least 4 characters long
            exit();
        }
    
        // Check phone number format
        if (!preg_match('/^\d{10}$/', $phoneNumber)) {
            header("Location: /tasks/authentication/register.php?error=7"); // Invalid phone number format
            exit();
        }
    
        // Check password length and numeric character
        if (strlen($password) < 6 || !preg_match('/\d/', $password)) {
            header("Location: /tasks/authentication/register.php?error=8"); // Password must be at least 6 characters long and contain at least one numeric character
            exit();
        }
    
        // Check if username, email, or phone number already exist
        if (User::findByUsername($this->db->getPdo(), $username)) {
            header("Location: /tasks/authentication/register.php?error=1"); // Username already exists
            exit();
        }
        if (User::findByEmail($this->db->getPdo(), $email)) {
            header("Location: /tasks/authentication/register.php?error=2"); // Email already exists
            exit();
        }
        if (User::findByPhoneNumber($this->db->getPdo(), $phoneNumber)) {
            header("Location: /tasks/authentication/register.php?error=3"); // Phone number already exists
            exit();
        }
    
        // Create a new User object
        $user = new User($this->db->getPdo(), $username, $password, $email, $phoneNumber);
    
        // Save the user to the database
        if ($user->save()) {
            header("Location: /tasks/authentication/login.php?registered=1"); // Registration successful
            exit();
        } else {
            header("Location: /tasks/authentication/register.php?error=5"); // Registration failed
            exit();
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
