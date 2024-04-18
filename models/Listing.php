<?php

class Listing {
    private $id;
    private $title;
    private $description;
    private $imagePath; // New property for storing image file path
    private $email;
    private $phoneNumber;
    private $pdo;

    public function __construct(PDO $pdo, $id=null, $title, $description, $imagePath, $email, $phoneNumber) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImagePath() {
        return $this->imagePath;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    // Method to save the listing to the database
    public function save() {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO listing (title, description, image_path, email, phoneNumber) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$this->title, $this->description, $this->imagePath, $this->email, $this->phoneNumber]);
            $this->id = $this->pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to handle file upload and set image path
    public function uploadImage($file) {
        // Check if file is uploaded successfully
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Handle file upload error
            return false;
        }

        // Generate a unique file name to prevent conflicts
        $filename = uniqid() . '_' . basename($file['name']);

        // Specify the directory where uploaded files will be stored
        $uploadDir = 'uploads/';

        // Move the uploaded file to the upload directory
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $this->imagePath = $destination;
            return $destination;
        } else {
            // Handle file move error
            return false;
        }
    }


    // Method to find a listing by its ID
    public static function findById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM listing WHERE id = ?");
        $stmt->execute([$id]);
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($listing) {
            // Change 'imagePath' to 'image'
            $instance = new self($pdo, $listing['id'], $listing['title'], $listing['description'], $listing['image_path'], $listing['email'], $listing['phoneNumber']);
            return $instance;
        }
        return null;
    }


    public static function findByTitle(PDO $pdo, $title) {
        $stmt = $pdo->prepare("SELECT * FROM listing WHERE title = ?");
        $stmt->execute([$title]);
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($listing) {
            $instance = new self($pdo, $listing['id'], $listing['title'], $listing['description'], $listing['imagePath'], $listing['email'], $listing['phoneNumber']);
            
            return $instance;
        }
        return null;
    }

    // Method to edit the listing
    public function edit($title, $description, $image, $email, $phoneNumber) {
        try {
            // Retrieve the current listing details
            $currentListing = self::findById($this->pdo, $this->id);
            if (!$currentListing) {
                return false; // Current listing not found
            }
    
            
            $newListing = new self($this->pdo, null, $title, $description, null, $email, $phoneNumber);
            $uploadResult = $this->uploadImage($image);
            if (!$uploadResult) {
                return false; 
            }
    
            
            $stmt = $this->pdo->prepare("UPDATE listing SET title = ?, description = ?, image_path = ?, email = ?, phoneNumber = ? WHERE id = ?");
            $stmt->execute([$newListing->title, $newListing->description, $uploadResult, $newListing->email, $newListing->phoneNumber, $this->id]);
        
        
    
            return true; 
        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
            return false; 
        }
    }
    

    

    public function delete() {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM listing WHERE id = ?");
            $stmt->execute([$this->id]);
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

?>
