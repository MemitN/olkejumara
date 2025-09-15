<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['email']) || !validateEmail($input['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email address required']);
    exit;
}

$email = sanitizeInput($input['email']);

try {
    $booking = new SafariBooking();
    
    if ($booking->subscribeNewsletter($email)) {
        // Send welcome email
        $subject = "Welcome to Olkeju Mara Tours Newsletter!";
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #059669;'>Welcome to Our Safari Family!</h2>
                <p>Thank you for subscribing to the Olkeju Mara Tours newsletter!</p>
                <p>You'll now receive:</p>
                <ul>
                    <li>Exclusive safari deals and discounts</li>
                    <li>Wildlife migration updates</li>
                    <li>Photography tips from our expert guides</li>
                    <li>Cultural insights about Maasai traditions</li>
                </ul>
                <p>Get ready for your next adventure in the Maasai Mara!</p>
                <p style='color: #059669; font-weight: bold;'>Asante sana (Thank you)!</p>
                <p>The Olkeju Mara Tours Team</p>
            </div>
        </body>
        </html>
        ";
        
        sendEmail($email, $subject, $message, true);
        
        echo json_encode([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter!'
        ]);
    } else {
        throw new Exception('Failed to subscribe');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Subscription failed. Please try again.']);
}
?>
