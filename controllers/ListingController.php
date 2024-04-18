<?php

require_once __DIR__ . '/../models/Listing.php';

class ListingController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addListing($title, $description, $email, $phoneNumber, $file) {
        // Create a new Listing instance
        $listing = new Listing($this->db->getPdo(), null, $title, $description, $file, $email, $phoneNumber);
 
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Upload image file
            if ($listing->uploadImage($file)) {
                return $listing->save();
            } else {
                // Handle image upload error
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

    public function getAllListings() {
        $pdo = $this->db->getPdo();
        $stmt = $pdo->query("SELECT * FROM listing");
        $listings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $listings[] = new Listing($pdo, $row['id'], $row['title'], $row['description'], $row['image_path'], $row['email'], $row['phoneNumber']);
        }
        return $listings;
    }

    public function editListing($id, $title, $description, $image, $email, $phoneNumber) {
        $listing = Listing::findById($this->db->getPdo(), $id);
        if ($listing) {
            return $listing->edit($title, $description, $image, $email, $phoneNumber);
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
