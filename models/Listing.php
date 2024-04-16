<?php

class Listing {
    private $id;
    private $title;
    private $description;
    private $image;
    private $email;
    private $phoneNumber;
    private $pdo;

    public function __construct(PDO $pdo, $id=null, $title, $description, $image, $email, $phoneNumber) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
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

    public function getImage() {
        return $this->image;
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
            $stmt = $this->pdo->prepare("INSERT INTO listing (title, description, image, email, phoneNumber) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$this->title, $this->description, $this->image, $this->email, $this->phoneNumber]);
            $this->id = $this->pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to find a listing by its ID
    public static function findById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM listing WHERE id = ?");
        $stmt->execute([$id]);
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($listing) {
            $instance = new self($pdo, $listing['id'], $listing['title'], $listing['description'], $listing['image'], $listing['email'], $listing['phoneNumber']);
            
            return $instance;
        }
        return null;
    }


    public static function findByTitle(PDO $pdo, $title) {
        $stmt = $pdo->prepare("SELECT * FROM listing WHERE title = ?");
        $stmt->execute([$title]);
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($listing) {
            $instance = new self($pdo, $listing['id'], $listing['title'], $listing['description'], $listing['image'], $listing['email'], $listing['phoneNumber']);
            
            return $instance;
        }
        return null;
    }

    // Method to edit the listing
    public function edit($title, $description, $image, $email, $phoneNumber) {
        try {
            $stmt = $this->pdo->prepare("UPDATE listing SET title = ?, description = ?, image = ?, email = ?, phoneNumber = ? WHERE id = ?");
            $stmt->execute([$title, $description, $image, $email, $phoneNumber, $this->id]);
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to delete the listing
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
