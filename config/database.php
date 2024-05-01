<?php

class Database {
    private $host;
    private $user;
    private $password;
    private $dbname;
    private $pdo;

    // Constructor to establish connection
    public function __construct($host, $user, $password, $dbname) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;

        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            // Automatically create the tables if they don't exist
            $this->createUserTable();
            $this->createListingTable();
            
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

        // Create the "user" table if it doesn't exist
        private function createUserTable() {
            $query = "
                CREATE TABLE IF NOT EXISTS `user` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `username` VARCHAR(255) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `address` TEXT,
                    `bio` TEXT,
                    `profile_picture` VARCHAR(255),
                    `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
    
            $this->pdo->exec($query);
        }
    
        // Get the PDO object
        public function getPdo() {
            return $this->pdo;
        }

    // Create the "listing" table if it doesn't exist
    // Create the "listing" table if it doesn't exist
    private function createListingTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS `listing` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `image_path` VARCHAR(255),
                `email` VARCHAR(255) NOT NULL,
                `phoneNumber` VARCHAR(20) NOT NULL,
                `user_id` INT NOT NULL,
                `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
            )
        ";

        $this->pdo->exec($query);
    }



}
?>
