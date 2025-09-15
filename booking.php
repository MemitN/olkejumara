<?php
session_start();
require_once 'includes/booking_handler.php';

// Initialize variables
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingHandler = new BookingHandler();
    
    // Prepare data for booking
    $booking_data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'destination' => trim($_POST['destination'] ?? ''),
        'package_type' => trim($_POST['package_type'] ?? ''),
        'travel_date' => trim($_POST['travel_date'] ?? ''),
        'number_of_people' => intval($_POST['number_of_people'] ?? 1),
        'budget_range' => trim($_POST['budget_range'] ?? ''),
        'special_requests' => trim($_POST['special_requests'] ?? '')
    ];
    
    $result = $bookingHandler->createBooking($booking_data);
    
    if ($result['success']) {
        $success_message = $result['message'] . " Your booking ID is: <strong>OMT-" . $result['booking_id'] . "</strong>";
        // Clear form data after successful submission
        $booking_data = array_fill_keys(array_keys($booking_data), '');
        $booking_data['number_of_people'] = 1;
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Safari - Olkeju Mara Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Top Contact Bar -->
    <div class="top-contact-bar">
        <div class="top-contact-content">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> +254 700 123 456</span>
                <span><i class="fas fa-envelope"></i> info@olkejumaratours.com</span>
            </div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Enhanced Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-paw me-2"></i>Olkeju Mara Tours
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="packagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Safari Packages
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.html#day-trips">Day Trips</a></li>
                            <li><a class="dropdown-item" href="index.html#multi-day">Multi-Day Safaris</a></li>
                            <li><a class="dropdown-item" href="index.html#luxury">Luxury Packages</a></li>
                            <li><a class="dropdown-item" href="index.html#budget">Budget Safaris</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="destinations.html">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.html">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="booking.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('images/safari.jpg')"></div>
            <div class="hero-slide" style="background-image: url('images/mrnp.jfif')"></div>
            <div class="hero-slide" style="background-image: url('images/mombasa marine.jfif')"></div>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Book Your Dream Safari</h1>
            <p>Fill out the form below and we'll create the perfect Maasai Mara experience for you</p>
            <div class="hero-buttons">
                <a href="#booking-form" class="btn btn-primary">
                    <i class="fas fa-calendar-alt me-2"></i>Start Booking
                </a>
                <button onclick="bookWhatsApp()" class="btn btn-warning">
                    <i class="fab fa-whatsapp me-2"></i>Quick Chat
                </button>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <section class="section" id="booking-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h3 class="mb-0">
                                <i class="fas fa-paw me-2"></i>
                                Safari Booking Form
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="booking.php" id="bookingForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($booking_data['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($booking_data['email'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($booking_data['phone'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="number_of_people" class="form-label">Number of People *</label>
                                        <select class="form-control" id="number_of_people" name="number_of_people" required>
                                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                                <option value="<?php echo $i; ?>" 
                                                    <?php echo (isset($booking_data['number_of_people']) && $booking_data['number_of_people'] == $i) ? 'selected' : ''; ?>>
                                                    <?php echo $i; ?> <?php echo $i == 1 ? 'Person' : 'People'; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="destination" class="form-label">Preferred Destination *</label>
                                    <select class="form-control" id="destination" name="destination" required>
                                        <option value="">Select a destination</option>
                                        <option value="Maasai Mara National Reserve" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Maasai Mara National Reserve') ? 'selected' : ''; ?>>Maasai Mara National Reserve</option>
                                        <option value="Amboseli National Park" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Amboseli National Park') ? 'selected' : ''; ?>>Amboseli National Park</option>
                                        <option value="Samburu National Reserve" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Samburu National Reserve') ? 'selected' : ''; ?>>Samburu National Reserve</option>
                                        <option value="Lake Nakuru National Park" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Lake Nakuru National Park') ? 'selected' : ''; ?>>Lake Nakuru National Park</option>
                                        <option value="Tsavo National Parks" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Tsavo National Parks') ? 'selected' : ''; ?>>Tsavo National Parks</option>
                                        <option value="Lake Naivasha" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Lake Naivasha') ? 'selected' : ''; ?>>Lake Naivasha</option>
                                        <option value="Aberdare National Park" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Aberdare National Park') ? 'selected' : ''; ?>>Aberdare National Park</option>
                                        <option value="Diani Beach" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Diani Beach') ? 'selected' : ''; ?>>Diani Beach</option>
                                        <option value="Multi-Destination Safari" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Multi-Destination Safari') ? 'selected' : ''; ?>>Multi-Destination Safari</option>
                                        <option value="Custom Safari" <?php echo (isset($booking_data['destination']) && $booking_data['destination'] == 'Custom Safari') ? 'selected' : ''; ?>>Custom Safari (Please specify in special requests)</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="travel_date" class="form-label">Preferred Travel Date *</label>
                                        <input type="date" class="form-control" id="travel_date" name="travel_date" 
                                               value="<?php echo htmlspecialchars($booking_data['travel_date'] ?? ''); ?>" 
                                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="budget_range" class="form-label">Budget Range (USD)</label>
                                        <select class="form-control" id="budget_range" name="budget_range">
                                            <option value="">Select budget range</option>
                                            <option value="Under $500" <?php echo (isset($booking_data['budget_range']) && $booking_data['budget_range'] == 'Under $500') ? 'selected' : ''; ?>>Under $500 per person</option>
                                            <option value="$500 - $1000" <?php echo (isset($booking_data['budget_range']) && $booking_data['budget_range'] == '$500 - $1000') ? 'selected' : ''; ?>>$500 - $1000 per person</option>
                                            <option value="$1000 - $2000" <?php echo (isset($booking_data['budget_range']) && $booking_data['budget_range'] == '$1000 - $2000') ? 'selected' : ''; ?>>$1000 - $2000 per person</option>
                                            <option value="$2000 - $5000" <?php echo (isset($booking_data['budget_range']) && $booking_data['budget_range'] == '$2000 - $5000') ? 'selected' : ''; ?>>$2000 - $5000 per person</option>
                                            <option value="Above $5000" <?php echo (isset($booking_data['budget_range']) && $booking_data['budget_range'] == 'Above $5000') ? 'selected' : ''; ?>>Above $5000 per person</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="package_type" class="form-label">Package Type *</label>
                                    <select class="form-control" id="package_type" name="package_type" required>
                                        <option value="">Select package type</option>
                                        <option value="Day Trip" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Day Trip') ? 'selected' : ''; ?>>Day Trip Safari</option>
                                        <option value="2-3 Days Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == '2-3 Days Safari') ? 'selected' : ''; ?>>2-3 Days Safari</option>
                                        <option value="4-7 Days Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == '4-7 Days Safari') ? 'selected' : ''; ?>>4-7 Days Safari</option>
                                        <option value="Extended Safari (8+ Days)" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Extended Safari (8+ Days)') ? 'selected' : ''; ?>>Extended Safari (8+ Days)</option>
                                        <option value="Luxury Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Luxury Safari') ? 'selected' : ''; ?>>Luxury Safari</option>
                                        <option value="Budget Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Budget Safari') ? 'selected' : ''; ?>>Budget Safari</option>
                                        <option value="Photography Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Photography Safari') ? 'selected' : ''; ?>>Photography Safari</option>
                                        <option value="Cultural Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Cultural Safari') ? 'selected' : ''; ?>>Cultural Safari</option>
                                        <option value="Balloon Safari" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Balloon Safari') ? 'selected' : ''; ?>>Hot Air Balloon Safari</option>
                                        <option value="Custom Package" <?php echo (isset($booking_data['package_type']) && $booking_data['package_type'] == 'Custom Package') ? 'selected' : ''; ?>>Custom Package</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="special_requests" class="form-label">Special Requests or Comments</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="4" 
                                              placeholder="Any special requirements, dietary restrictions, accessibility needs, preferred accommodations, or other requests..."><?php echo htmlspecialchars($booking_data['special_requests'] ?? ''); ?></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Submit Booking Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-5" style="background-color: var(--light-color);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h3>What Happens Next?</h3>
                    <p class="text-muted">Our simple 3-step process to your dream safari</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h5>1. Instant Confirmation</h5>
                    <p class="text-muted">Your booking request is saved to our database and you'll receive an instant confirmation with your booking ID.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fab fa-whatsapp fa-3x text-success"></i>
                    </div>
                    <h5>2. WhatsApp Contact</h5>
                    <p class="text-muted">Our safari experts will contact you via WhatsApp within 2 hours to discuss your preferences and customize your trip.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-paw fa-3x text-warning"></i>
                    </div>
                    <h5>3. Safari Adventure</h5>
                    <p class="text-muted">Once everything is confirmed and arranged, you're ready for your amazing Kenyan safari adventure!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-paw me-2"></i>Olkeju Mara Tours
                    </h5>
                    <p>Your gateway to authentic Kenyan safari experiences. Locally owned and operated with a passion for wildlife conservation.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="destinations.html">Destinations</a></li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="booking.php">Book Now</a></li>
                        <li><a href="about.html">About Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Safari Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Day Safaris</a></li>
                        <li><a href="#">Multi-Day Packages</a></li>
                        <li><a href="#">Cultural Tours</a></li>
                        <li><a href="#">Photography Safaris</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Maasai Mara, Narok County, Kenya</li>
                        <li><i class="fas fa-phone me-2"></i>+254 700 123 456</li>
                        <li><i class="fab fa-whatsapp me-2"></i>+254 700 123 456</li>
                        <li><i class="fas fa-envelope me-2"></i>info@olkejumaratours.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; 2024 Olkeju Mara Tours. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="me-3">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-float" onclick="bookWhatsApp()">
        <i class="fab fa-whatsapp"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        function bookWhatsApp() {
            const message = `Hi! I'm interested in booking a safari with Olkeju Mara Tours. Could you please help me with the booking process?`;
            const whatsappUrl = `https://wa.me/254700123456?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Form enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            
            // Form submission enhancement
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
