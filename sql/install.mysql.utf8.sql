-- /modules/mod_whiteleaf_booking/sql/install.mysql.utf8.sql

-- Table for rooms
CREATE TABLE IF NOT EXISTS `#__whiteleaf_rooms` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `alias` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `room_type` varchar(50) NOT NULL,
    `capacity` int(11) NOT NULL DEFAULT 2,
    `price` decimal(10,2) NOT NULL,
    `base_price` decimal(10,2) NOT NULL,
    `weekend_price` decimal(10,2) NOT NULL,
    `special_price` decimal(10,2) DEFAULT NULL,
    `amenities` text,
    `room_size` varchar(50),
    `bed_type` varchar(100),
    `main_image` varchar(255),
    `gallery_images` text,
    `max_adults` int(11) NOT NULL DEFAULT 2,
    `max_children` int(11) NOT NULL DEFAULT 2,
    `extra_bed_available` tinyint(1) NOT NULL DEFAULT 0,
    `extra_bed_price` decimal(10,2) DEFAULT NULL,
    `room_number` varchar(50),
    `floor` varchar(50),
    `view_type` varchar(100),
    `published` tinyint(1) NOT NULL DEFAULT 1,
    `ordering` int(11) NOT NULL DEFAULT 0,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` int(11) NOT NULL,
    `modified` datetime DEFAULT NULL,
    `modified_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Table for room availability
CREATE TABLE IF NOT EXISTS `#__whiteleaf_room_availability` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `room_id` int(11) NOT NULL,
    `date` date NOT NULL,
    `status` enum('available','booked','maintenance') NOT NULL DEFAULT 'available',
    `price_override` decimal(10,2) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `room_id` (`room_id`),
    KEY `date` (`date`),
    CONSTRAINT `fk_room_availability_room` FOREIGN KEY (`room_id`) 
    REFERENCES `#__whiteleaf_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Table for bookings
CREATE TABLE IF NOT EXISTS `#__whiteleaf_bookings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `booking_number` varchar(50) NOT NULL,
    `room_id` int(11) NOT NULL,
    `check_in` date NOT NULL,
    `check_out` date NOT NULL,
    `guest_name` varchar(255) NOT NULL,
    `guest_email` varchar(255) NOT NULL,
    `guest_phone` varchar(50),
    `num_adults` int(11) NOT NULL DEFAULT 1,
    `num_children` int(11) NOT NULL DEFAULT 0,
    `special_requests` text,
    `total_price` decimal(10,2) NOT NULL,
    `payment_status` enum('pending','paid','cancelled','refunded') NOT NULL DEFAULT 'pending',
    `booking_status` enum('pending','confirmed','checked_in','checked_out','cancelled') NOT NULL DEFAULT 'pending',
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `modified` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `booking_number` (`booking_number`),
    KEY `room_id` (`room_id`),
    CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) 
    REFERENCES `#__whiteleaf_rooms` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;