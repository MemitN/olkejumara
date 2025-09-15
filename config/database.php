<?php
// Session configuration - must be set before session_start()
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_strict_mode', 1);
}

// Database configuration for Olkeju Mara Tours
// Update these credentials according to your hosting environment

class Database {
    private $host = 'localhost';
    private $db_name = 'olkeju_mara_tours';
    private $username = 'root'; // Change to your database username
    private $password = ''; // Change to your database password
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com'); // Change to your SMTP host
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com'); // Change to your email
define('SMTP_PASSWORD', 'your-app-password'); // Change to your app password
define('FROM_EMAIL', 'info@olkejumaratours.com');
define('FROM_NAME', 'Olkeju Mara Tours');

// WhatsApp configuration
define('WHATSAPP_NUMBER', '+254713525190'); // Updated phone number
define('WHATSAPP_MESSAGE_PREFIX', 'Hello Olkeju Mara Tours! I am interested in booking a safari: ');

// Site configuration
define('SITE_URL', 'https://olkejumaratours.com'); // Change to your domain
define('SITE_NAME', 'Olkeju Mara Tours');
define('ADMIN_EMAIL', 'admin@olkejumaratours.com');

// Payment configuration (for future integration)
define('MPESA_CONSUMER_KEY', 'your-mpesa-consumer-key');
define('MPESA_CONSUMER_SECRET', 'your-mpesa-consumer-secret');
define('MPESA_SHORTCODE', 'your-shortcode');
define('MPESA_PASSKEY', 'your-passkey');

// Security settings
define('JWT_SECRET', 'your-jwt-secret-key-change-this');
define('ENCRYPTION_KEY', 'your-encryption-key-change-this');

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_PATH', 'uploads/');


// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Nairobi');
?>
