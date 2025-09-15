-- Olkeju Mara Tours Database Schema
-- Complete database structure for safari booking system

CREATE DATABASE IF NOT EXISTS olkeju_mara_tours;
USE olkeju_mara_tours;

-- Users table for customer management
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    country VARCHAR(50),
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Safari packages table
CREATE TABLE safari_packages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_days INT NOT NULL,
    price_per_person DECIMAL(10,2) NOT NULL,
    max_participants INT DEFAULT 8,
    category ENUM('day-trips', 'multi-day', 'luxury', 'budget', 'family') NOT NULL,
    includes TEXT,
    excludes TEXT,
    itinerary TEXT,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    package_id INT,
    booking_reference VARCHAR(20) UNIQUE NOT NULL,
    travel_date DATE NOT NULL,
    participants INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'partial', 'paid', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (package_id) REFERENCES safari_packages(id) ON DELETE CASCADE
);

-- Contact inquiries table
CREATE TABLE contact_inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'responded', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers table
CREATE TABLE newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reviews and testimonials table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    booking_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    review_text TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Gallery images table
CREATE TABLE gallery_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    alt_text VARCHAR(200),
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog posts table for safari tips and news
CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    category VARCHAR(50),
    tags VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample safari packages
INSERT INTO safari_packages (name, description, duration_days, price_per_person, category, includes, excludes) VALUES
('Maasai Mara Day Safari', 'Experience the magic of Maasai Mara in a single day. Perfect for those with limited time.', 1, 150.00, 'day-trips', 'Game drive in 4WD vehicle, Professional guide, Lunch at Mara River', 'Accommodation, Personal expenses'),
('3-Day Mara Adventure', 'Immerse yourself in the wilderness with our comprehensive 3-day safari experience.', 3, 450.00, 'multi-day', '2 nights accommodation, All meals included, Multiple game drives, Maasai village visit', 'International flights, Travel insurance'),
('Luxury Mara Experience', 'Indulge in the ultimate safari luxury with premium accommodations and exclusive experiences.', 4, 1200.00, 'luxury', '5-star lodge accommodation, Hot air balloon safari, Private game drives, Champagne breakfast', 'International flights, Personal shopping'),
('Great Migration Special', 'Witness the spectacular Great Migration - one of nature\'s most incredible phenomena.', 4, 650.00, 'multi-day', '4 days / 3 nights, River crossing viewing, Expert migration guide, Photography support', 'Camera equipment, Travel insurance'),
('Budget Mara Explorer', 'Affordable safari experience without compromising on the adventure and wildlife viewing.', 2, 200.00, 'budget', '2 days / 1 night camping, Shared transportation, Basic meals included, Group game drives', 'Sleeping bags, Personal items'),
('Family Safari Adventure', 'Perfect for families with children - educational, safe, and unforgettable safari experience.', 3, 350.00, 'family', 'Child-friendly activities, Educational programs, Family accommodation, Special kids meals', 'Personal expenses, Extra activities');

-- Insert sample gallery images
INSERT INTO gallery_images (title, description, image_url, category, alt_text) VALUES
('Lions Pride', 'Majestic lions resting in the savanna', '/images/gallery/lions.jpg', 'wildlife', 'Pride of lions in Maasai Mara'),
('Elephant Family', 'Gentle giants crossing the Mara River', '/images/gallery/elephants.jpg', 'wildlife', 'Elephant family at Mara River'),
('Maasai Culture', 'Traditional Maasai warriors in colorful attire', '/images/gallery/maasai.jpg', 'culture', 'Maasai cultural experience'),
('Sunset Safari', 'Golden sunset over the African savanna', '/images/gallery/sunset.jpg', 'landscape', 'Beautiful Maasai Mara sunset'),
('Cheetah Hunt', 'Fastest land animal in action', '/images/gallery/cheetah.jpg', 'wildlife', 'Cheetah hunting in the wild'),
('Balloon Safari', 'Hot air balloon floating over the Mara', '/images/gallery/balloon.jpg', 'activities', 'Hot air balloon safari experience');

-- Insert sample testimonials
INSERT INTO reviews (user_id, rating, title, review_text, status) VALUES
(NULL, 5, 'Absolutely Amazing Experience!', 'The safari exceeded all our expectations. Our guide was incredibly knowledgeable and we saw all the Big Five. Highly recommend Olkeju Mara Tours!', 'approved'),
(NULL, 5, 'Perfect Family Adventure', 'Our family had the most wonderful time. The guides were patient with our children and made the experience educational and fun. Will definitely return!', 'approved'),
(NULL, 5, 'Authentic Cultural Experience', 'Not just wildlife - we learned so much about Maasai culture. The community visit was respectful and genuine. Thank you for an unforgettable journey!', 'approved');

-- Insert sample blog posts
INSERT INTO blog_posts (title, slug, content, excerpt, category, status) VALUES
('Best Time to Visit Maasai Mara', 'best-time-visit-maasai-mara', 'The Maasai Mara offers incredible wildlife viewing year-round, but certain times are particularly special...', 'Discover the optimal times for your Maasai Mara safari adventure', 'travel-tips', 'published'),
('The Great Migration: Nature\'s Greatest Show', 'great-migration-natures-greatest-show', 'Every year, over 1.5 million wildebeest make the treacherous journey across the Mara River...', 'Learn about the spectacular Great Migration phenomenon', 'wildlife', 'published'),
('Maasai Culture and Traditions', 'maasai-culture-traditions', 'The Maasai people have lived in harmony with wildlife for centuries, maintaining their rich cultural heritage...', 'Explore the fascinating culture of the Maasai people', 'culture', 'published');
