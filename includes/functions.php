<?php
// Utility functions for Olkeju Mara Tours website
require_once 'config/database.php';

class SafariBooking {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function createBooking($data) {
        $query = "INSERT INTO bookings (user_id, package_id, booking_reference, travel_date, participants, total_amount, special_requests) 
                  VALUES (:user_id, :package_id, :booking_reference, :travel_date, :participants, :total_amount, :special_requests)";
        
        $stmt = $this->db->prepare($query);
        
        // Generate unique booking reference
        $booking_reference = 'OLK' . date('Y') . strtoupper(substr(uniqid(), -6));
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':package_id', $data['package_id']);
        $stmt->bindParam(':booking_reference', $booking_reference);
        $stmt->bindParam(':travel_date', $data['travel_date']);
        $stmt->bindParam(':participants', $data['participants']);
        $stmt->bindParam(':total_amount', $data['total_amount']);
        $stmt->bindParam(':special_requests', $data['special_requests']);
        
        if ($stmt->execute()) {
            return $booking_reference;
        }
        return false;
    }
    
    public function getPackages($category = null) {
        $query = "SELECT * FROM safari_packages WHERE status = 'active'";
        if ($category) {
            $query .= " AND category = :category";
        }
        $query .= " ORDER BY price_per_person ASC";
        
        $stmt = $this->db->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function saveContactInquiry($data) {
        $query = "INSERT INTO contact_inquiries (name, email, phone, subject, message) 
                  VALUES (:name, :email, :phone, :subject, :message)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':subject', $data['subject']);
        $stmt->bindParam(':message', $data['message']);
        
        return $stmt->execute();
    }
    
    public function subscribeNewsletter($email) {
        $query = "INSERT INTO newsletter_subscribers (email) VALUES (:email) 
                  ON DUPLICATE KEY UPDATE status = 'active', subscribed_at = CURRENT_TIMESTAMP";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        
        return $stmt->execute();
    }
    
    public function getTestimonials($limit = 6) {
        $query = "SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getGalleryImages($category = null, $limit = 12) {
        $query = "SELECT * FROM gallery_images WHERE status = 'active'";
        if ($category) {
            $query .= " AND category = :category";
        }
        $query .= " ORDER BY display_order ASC, created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

// Email sending function
function sendEmail($to, $subject, $message, $isHTML = true) {
    $headers = array(
        'From' => FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To' => FROM_EMAIL,
        'Content-Type' => $isHTML ? 'text/html; charset=UTF-8' : 'text/plain; charset=UTF-8'
    );
    
    return mail($to, $subject, $message, implode("\r\n", array_map(
        function($k, $v) { return "$k: $v"; },
        array_keys($headers),
        $headers
    )));
}

// WhatsApp message formatting
function formatWhatsAppMessage($type, $data) {
    $baseMessage = "Hello Olkeju Mara Tours! ";
    
    switch ($type) {
        case 'booking':
            return $baseMessage . "I would like to book the {$data['package']} package for {$data['participants']} people on {$data['date']}. Please send me more details.";
        
        case 'inquiry':
            return $baseMessage . "I'm interested in your safari packages. Could you please provide more information about: {$data['inquiry']}";
        
        case 'contact':
            return $baseMessage . "I have a question: {$data['message']}";
        
        default:
            return $baseMessage . "I'm interested in your safari services. Please contact me.";
    }
}

// Security functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Image upload function
function uploadImage($file, $destination = 'uploads/') {
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }
    
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new RuntimeException('Exceeded filesize limit.');
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new RuntimeException('Invalid file format.');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = sprintf('%s.%s', sha1_file($file['tmp_name']), $extension);
    
    if (!move_uploaded_file($file['tmp_name'], $destination . $filename)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    
    return $destination . $filename;
}

// Date formatting functions
function formatSafariDate($date) {
    return date('F j, Y', strtotime($date));
}

function getSeasonInfo($date) {
    $month = date('n', strtotime($date));
    
    if ($month >= 7 && $month <= 10) {
        return ['season' => 'Great Migration', 'description' => 'Best time to witness the wildebeest migration'];
    } elseif ($month >= 12 || $month <= 3) {
        return ['season' => 'Calving Season', 'description' => 'Perfect for seeing newborn animals'];
    } else {
        return ['season' => 'Green Season', 'description' => 'Lush landscapes and fewer crowds'];
    }
}

// Price calculation
function calculatePackagePrice($packageId, $participants, $date) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT price_per_person FROM safari_packages WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $packageId);
    $stmt->execute();
    
    $package = $stmt->fetch();
    if (!$package) {
        return false;
    }
    
    $basePrice = $package['price_per_person'];
    $seasonInfo = getSeasonInfo($date);
    
    // Apply seasonal pricing
    $multiplier = 1.0;
    if ($seasonInfo['season'] === 'Great Migration') {
        $multiplier = 1.3; // 30% increase during migration
    } elseif ($seasonInfo['season'] === 'Calving Season') {
        $multiplier = 1.2; // 20% increase during calving
    }
    
    // Group discounts
    if ($participants >= 6) {
        $multiplier *= 0.9; // 10% discount for groups of 6+
    } elseif ($participants >= 4) {
        $multiplier *= 0.95; // 5% discount for groups of 4+
    }
    
    return round($basePrice * $participants * $multiplier, 2);
}
?>
