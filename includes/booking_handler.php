<?php
require_once 'config/database.php';

class BookingHandler {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function createBooking($data) {
        try {
            // Validate required fields
            $required_fields = ['name', 'email', 'phone', 'destination', 'package_type', 'travel_date', 'number_of_people'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => 'Please fill in all required fields.'
                    ];
                }
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Please enter a valid email address.'
                ];
            }
            
            // Validate travel date (must be in the future)
            $travel_date = new DateTime($data['travel_date']);
            $today = new DateTime();
            if ($travel_date <= $today) {
                return [
                    'success' => false,
                    'message' => 'Travel date must be in the future.'
                ];
            }
            
            // Generate booking ID
            $booking_id = $this->generateBookingId();
            
            // Insert booking into database
            $query = "INSERT INTO bookings (
                booking_id, name, email, phone, destination, package_type, 
                travel_date, number_of_people, budget_range, special_requests, 
                booking_status, created_at
            ) VALUES (
                :booking_id, :name, :email, :phone, :destination, :package_type,
                :travel_date, :number_of_people, :budget_range, :special_requests,
                'pending', NOW()
            )";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':destination', $data['destination']);
            $stmt->bindParam(':package_type', $data['package_type']);
            $stmt->bindParam(':travel_date', $data['travel_date']);
            $stmt->bindParam(':number_of_people', $data['number_of_people']);
            $stmt->bindParam(':budget_range', $data['budget_range']);
            $stmt->bindParam(':special_requests', $data['special_requests']);
            
            if ($stmt->execute()) {
                // Send notification email (optional)
                $this->sendBookingNotification($data, $booking_id);
                
                return [
                    'success' => true,
                    'message' => 'Your booking request has been submitted successfully! We will contact you within 2 hours via WhatsApp.',
                    'booking_id' => $booking_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Sorry, there was an error processing your booking. Please try again or contact us directly.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Booking Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sorry, there was a technical error. Please try again or contact us directly.'
            ];
        }
    }
    
    private function generateBookingId() {
        // Generate unique booking ID with format: OMT-YYYYMMDD-XXXX
        $date = date('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $date . $random;
    }
    
    private function sendBookingNotification($data, $booking_id) {
        // Email notification to admin (optional - requires mail configuration)
        $to = "info@olkejumaratours.com";
        $subject = "New Safari Booking Request - OMT-" . $booking_id;
        
        $message = "New booking request received:\n\n";
        $message .= "Booking ID: OMT-" . $booking_id . "\n";
        $message .= "Name: " . $data['name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        $message .= "Phone: " . $data['phone'] . "\n";
        $message .= "Destination: " . $data['destination'] . "\n";
        $message .= "Package: " . $data['package_type'] . "\n";
        $message .= "Travel Date: " . $data['travel_date'] . "\n";
        $message .= "Number of People: " . $data['number_of_people'] . "\n";
        $message .= "Budget Range: " . $data['budget_range'] . "\n";
        $message .= "Special Requests: " . $data['special_requests'] . "\n";
        
        $headers = "From: noreply@olkejumaratours.com";
        
        // Uncomment the line below to enable email notifications
        // mail($to, $subject, $message, $headers);
    }
    
    public function getBookingById($booking_id) {
        try {
            $query = "SELECT * FROM bookings WHERE booking_id = :booking_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Booking Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllBookings($limit = 50, $offset = 0) {
        try {
            $query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get All Bookings Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function updateBookingStatus($booking_id, $status) {
        try {
            $query = "UPDATE bookings SET booking_status = :status, updated_at = NOW() WHERE booking_id = :booking_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':booking_id', $booking_id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update Booking Status Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
