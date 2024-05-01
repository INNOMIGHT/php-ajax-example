<?php

class User {
    private $id;
    private $username;
    private $password;
    private $email;
    private $address;
    private $bio;
    private $profilePhoto;
    private $pdo;

    public function __construct(PDO $pdo, $username, $password, $email, $address, $bio, $profilePhoto) {
        $this->pdo = $pdo;
        $this->username = $username;
        $this->password = $password; 
        $this->email = $email;
        $this->address = $address;
        $this->bio = $bio;
        $this->profilePhoto = $profilePhoto;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getBio() {
        return $this->bio;
    }

    public function getProfilePhoto() {
        return $this->profilePhoto;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function save() {
        try {
            // Hash the password before saving it
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
            $stmt = $this->pdo->prepare("INSERT INTO `user` (username, password, email, address, bio, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->username, $hashedPassword, $this->email, $this->address, $this->bio, $this->profilePhoto]);
            $this->id = $this->pdo->lastInsertId();
            
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function findById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $instance = new self($pdo, $user['username'], $user['password'], $user['email'], $user['address'], $user['bio'], $user['profile_picture']);
            $instance->setId($user['id']);
            return $instance;
        }
        return null;
    }

    public static function findByUsername(PDO $pdo, $username) {
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $instance = new self($pdo, $user['username'], $user['password'], $user['email'], $user['address'], $user['bio'], $user['profile_picture']);
            $instance->setId($user['id']);
            return $instance;
        }
        return null;
    }

    public static function findByEmail(PDO $pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $instance = new self($pdo, $user['username'], $user['password'], $user['email'], $user['address'], $user['bio'], $user['profile_picture']);
            $instance->setId($user['id']);
            return $instance;
        }
        return null;
    }

    // Method to retrieve all listings created by this user
    public function getListings() {
        $stmt = $this->pdo->prepare("SELECT * FROM `listing` WHERE user_id = ?");
        $stmt->execute([$this->id]);
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $listings;
    }

    public function uploadImage($file) {
        // Check if file is uploaded successfully
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Handle file upload error
            return false;
        }

        // Generate a unique file name to prevent conflicts
        $filename = uniqid() . '_' . basename($file['name']);

        // Specify the directory where uploaded files will be stored
        $uploadDir = 'profilePhotoUploads/';

        // Move the uploaded file to the upload directory
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $this->profilePhoto = $destination;
            return $destination;
        } else {
            // Handle file move error
            return false;
        }
    }
}
?>
