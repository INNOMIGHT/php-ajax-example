<?php

require_once __DIR__ . '/../models/Listing.php';

class ListingController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addListing($title, $description, $email, $phoneNumber, $file, $user_id) {
        // Create a new Listing instance
        $listing = new Listing($this->db->getPdo(), null, $title, $description, $file, $email, $phoneNumber, $user_id);
    
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Upload image file
            if ($listing->uploadImage($file)) {
                if ($listing->compressImage()) { 
                    return $listing->save();
                } else {
                    return false; 
                }
            } else {
                
                return false;
            }
        } else {
            // Handle file upload error
            return false;
        }
    }
    

    public function getListing($id) {
        return Listing::findById($this->db->getPdo(), $id);
    }

    public function getListingByTitle($title) {
        return Listing::findByTitle($this->db->getPdo(), $title);
    }

    public function getAllListings($userId) {
        $pdo = $this->db->getPdo();
        $stmt = $pdo->query("SELECT * FROM listing where user_id=$userId");
        $listings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $listings[] = new Listing($pdo, $row['id'], $row['title'], $row['description'], $row['image_path'], $row['email'], $row['phoneNumber'], $userId);
        }
        
        return $listings;
    }

    public function editListing($id, $title, $description, $image, $email, $phoneNumber, $userId) {
        $listing = Listing::findById($this->db->getPdo(), $id);
        if ($listing) {
            return $listing->edit($title, $description, $image, $email, $phoneNumber, $userId);
        }
        return false;
    }
    

    public function deleteListing($id) {
        $listing = Listing::findById($this->db->getPdo(), $id);
        if ($listing) {
            return $listing->delete();
        }
        return false;
    }
}

?>
