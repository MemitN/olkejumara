<?php
session_start();

// Initialize variables
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long";
    }
    
    if (empty($errors)) {
        // In a real application, you would:
        // 1. Save to database
        // 2. Send email to admin
        // 3. Send confirmation email to user
        
        // For this example, we'll just simulate success
        $success_message = "Thank you for your message! We'll get back to you within 24 hours.";
        
        // Clear form data after successful submission
        $name = $email = $phone = $subject = $message = '';
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Olkeju Mara Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Added Google Fonts to match other pages -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800;900&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Added top contact bar to match other pages -->
    <div class="top-contact-bar">
        <div class="top-contact-content">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> +254713525190</span>
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

    <!-- Updated navigation to match other pages with dropdown and proper styling -->
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
                            <li><a class="dropdown-item" href="#day-trips">Day Trips</a></li>
                            <li><a class="dropdown-item" href="#multi-day">Multi-Day Safaris</a></li>
                            <li><a class="dropdown-item" href="#luxury">Luxury Packages</a></li>
                            <li><a class="dropdown-item" href="#budget">Budget Safaris</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="destinations.html">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.html">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Updated page header to match other pages styling -->
    <section class="hero-section" style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/placeholder.svg?height=600&width=1920') center/cover;">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>We're here to help plan your perfect safari adventure</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-5">
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

                    <div class="form-container">
                        <h3 class="mb-4">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            Send Us a Message
                        </h3>
                        
                        <form method="POST" action="contact.php" id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject" class="form-label">Subject *</label>
                                        <select class="form-control" id="subject" name="subject" required>
                                            <option value="">Select a subject</option>
                                            <option value="general" <?php echo (isset($subject) && $subject == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                            <option value="booking" <?php echo (isset($subject) && $subject == 'booking') ? 'selected' : ''; ?>>Booking Question</option>
                                            <option value="support" <?php echo (isset($subject) && $subject == 'support') ? 'selected' : ''; ?>>Customer Support</option>
                                            <option value="feedback" <?php echo (isset($subject) && $subject == 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                                            <option value="partnership" <?php echo (isset($subject) && $subject == 'partnership') ? 'selected' : ''; ?>>Partnership</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="6" 
                                          placeholder="Please tell us how we can help you..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h3 class="mb-4">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            Get in Touch
                        </h3>
                        
                        <div class="contact-item mb-4">
                            <h5><i class="fas fa-building me-2 text-primary"></i>Office Address</h5>
                            <p class="text-muted">Maasai Mara National Reserve<br>Narok County, Kenya<br>East Africa</p>
                        </div>

                        <div class="contact-item mb-4">
                            <h5><i class="fas fa-phone me-2 text-success"></i>Phone Numbers</h5>
                            <p class="text-muted">
                                Main: +254713525190<br>
                                WhatsApp: +254713525190<br>
                                Emergency: +254713525190
                            </p>
                        </div>

                        <div class="contact-item mb-4">
                            <h5><i class="fas fa-envelope me-2 text-warning"></i>Email Addresses</h5>
                            <p class="text-muted">
                                General: info@olkejumaratours.com<br>
                                Bookings: bookings@olkejumaratours.com<br>
                                Support: support@olkejumaratours.com
                            </p>
                        </div>

                        <div class="contact-item mb-4">
                            <h5><i class="fas fa-clock me-2 text-info"></i>Business Hours</h5>
                            <p class="text-muted">
                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM<br>
                                Sunday: Closed<br>
                                <small>Emergency support available 24/7</small>
                            </p>
                        </div>

                        <div class="social-links-contact">
                            <h5 class="mb-3">Follow Us</h5>
                            <a href="#" class="btn btn-outline-primary me-2 mb-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="#" class="btn btn-outline-info me-2 mb-2">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                            <a href="#" class="btn btn-outline-success me-2 mb-2">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="#" class="btn btn-outline-danger me-2 mb-2">
                                <i class="fab fa-youtube"></i> YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section" style="background-color: var(--light-color);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="mb-4">Find Our Office</h3>
                    <div class="map-placeholder bg-secondary rounded" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                        <div class="text-white">
                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                            <h5>Interactive Map</h5>
                            <p>Maasai Mara National Reserve, Narok County, Kenya</p>
                            <small>In a real website, this would be an embedded Google Maps or similar service</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Updated footer to match other pages structure and styling -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-paw me-2"></i>Olkeju Mara Tours
                    </h5>
                    <p class="text-muted">Your gateway to authentic Maasai Mara safari experiences. Locally owned and operated with a passion for wildlife conservation.</p>
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
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Maasai Mara, Narok County, Kenya</li>
                        <li><i class="fas fa-phone me-2"></i>+254713525190</li>
                        <li><i class="fab fa-whatsapp me-2"></i>+254713525190</li>
                        <li><i class="fas fa-envelope me-2"></i>info@olkejumaratours.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 Olkeju Mara Tours. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Added WhatsApp floating button to match other pages -->
    <div class="whatsapp-float" onclick="bookWhatsApp()">
        <i class="fab fa-whatsapp"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const messageField = document.getElementById('message');
            
            // Character counter for message field
            const charCounter = document.createElement('small');
            charCounter.className = 'text-muted';
            messageField.parentNode.appendChild(charCounter);
            
            function updateCharCounter() {
                const length = messageField.value.length;
                charCounter.textContent = `${length} characters (minimum 10 required)`;
                
                if (length < 10) {
                    charCounter.className = 'text-danger';
                } else {
                    charCounter.className = 'text-muted';
                }
            }
            
            messageField.addEventListener('input', updateCharCounter);
            updateCharCounter();
            
            // Form submission enhancement
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="spinner me-2"></span>Sending...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
