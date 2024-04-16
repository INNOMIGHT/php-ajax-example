<?php

class User {
    private $id;
    private $username;
    private $password;
    private $email;
    private $phoneNumber;
    private $pdo;

    public function __construct(PDO $pdo, $username, $password, $email, $phoneNumber) {
        $this->pdo = $pdo;
        $this->username = $username;
        $this->password = $password; 
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
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

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setId($id) {
        $this->id = $id;
    }


    public static function findByEmail(PDO $pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? true : false;
    }
    
    public static function findByPhoneNumber(PDO $pdo, $phoneNumber) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone_number = ?");
        $stmt->execute([$phoneNumber]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? true : false;
    }
    

    public function save() {
        try {
            // Hash the password before saving it
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email, phone_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->username, $hashedPassword, $this->email, $this->phoneNumber]);
            $this->id = $this->pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    

    public static function findById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $instance = new self($pdo, $user['username'], $user['password'], $user['email'], $user['phone_number']);
            $instance->setId($user['id']);
            return $instance;
        }
        return null;
    }

    public static function findByUsername(PDO $pdo, $username) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $instance = new self($pdo, $user['username'], $user['password'], $user['email'], $user['phone_number']);
            $instance->setId($user['id']);
            return $instance;
        }
        return null;
    }

}
