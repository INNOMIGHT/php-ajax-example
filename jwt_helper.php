<?php
require_once 'vendor/autoload.php'; // Include Composer's autoloader

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper {
    private static $secretKey = "VaibhavSecret";
    private static $algorithm = 'HS256'; // You can change the algorithm as needed

    public static function encode($payload) {
        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }


    public static function decode($jwt) {
        $headers = new stdClass();
        return JWT::decode($jwt, new \Firebase\JWT\Key(self::$secretKey, self::$algorithm), $headers);
    }
    
    
    
}
?>
