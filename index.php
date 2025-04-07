<?php
// Start session before ANY output
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define data directory with absolute path
$data_dir = __DIR__ . '/data';
$data_file = $data_dir . '/site_data.json';

// Log important information for debugging
error_log("Index.php accessed. Admin mode: " . (isset($_GET['admin']) ? 'Yes' : 'No'));
error_log("Session authenticated: " . (isset($_SESSION['authenticated']) ? 'Yes' : 'No'));

// Create data directory if it doesn't exist
if (!file_exists($data_dir)) {
    if (!@mkdir($data_dir, 0755, true)) {
        // Handle directory creation failure
        $error_message = "Unable to create data directory. Please check permissions.";
        error_log($error_message);
    }
}

// Default fallback data
$default_data = array(
    'hero' => array(
        'title' => "DJ JEFF BONE",
        'tagline' => "DJ/Producer/Remixer",
        'button1_text' => "Listen Now",
        'button1_link' => "#music",
        'button2_text' => "Upcoming Shows",
        'button2_link' => "#events",
        'background_type' => "image",
        'background_image' => "assets/img/hero-bg.jpg",
        'background_video' => ""
    ),
    'about' => array(
        'title' => "ABOUT DJ JEFF BONE",
        'description' => "Jeff Bone is an electrifying DJ known for his unique blend of house, techno, and electro music. With over 10 years of experience, Jeff has performed at major clubs and festivals worldwide, creating unforgettable experiences through his innovative mixing techniques and energetic stage presence.",
        'image' => "assets/img/jeff-bone-profile.jpg",
        'stats' => array(
            'years' => '10+',
            'shows' => '500+',
            'countries' => '20+',
            'festivals' => '50+'
        ),
        'stats_titles' => array(
            'years' => 'Years Experience',
            'shows' => 'Shows',
            'countries' => 'Countries',
            'festivals' => 'Festivals'
        )
    ),
    'events' => array(
        array(
            'id' => 1,
            'date' => "2023-12-15",
            'venue' => "Club Enigma",
            'location' => "New York, NY",
            'image' => "assets/img/event-1.jpg"
        ),
        array(
            'id' => 2,
            'date' => "2024-01-20",
            'venue' => "Pulse Festival",
            'location' => "Miami, FL",
            'image' => "assets/img/event-2.jpg"
        ),
        array(
            'id' => 3,
            'date' => "2024-02-10",
            'venue' => "Underground",
            'location' => "Los Angeles, CA",
            'image' => "assets/img/event-3.jpg"
        )
    ),
    'music' => array(
        array(
            'id' => 1,
            'title' => "Summer Vibes Mix",
            'embed' => "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1223408135&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"
        ),
        array(
            'id' => 2,
            'title' => "Techno Fusion",
            'embed' => "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1223408135&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"
        )
    ),
    'videos' => array(
        array(
            'id' => 1,
            'title' => "Live at Club Enigma",
            'embed' => "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
            'description' => "Highlights from my recent performance at Club Enigma in New York."
        ),
        array(
            'id' => 2,
            'title' => "Miami Music Week",
            'embed' => "https://www.youtube.com/watch?v=y6120QOlsfU",
            'description' => "Behind the scenes at Miami Music Week 2023."
        )
    ),
    'gallery' => array(
        array('id' => 1, 'src' => "assets/img/gallery-1.jpg", 'alt' => "DJ Jeff Bone performing at Club Enigma"),
        array('id' => 2, 'src' => "assets/img/gallery-2.jpg", 'alt' => "Jeff Bone at Miami Music Week"),
        array('id' => 3, 'src' => "assets/img/gallery-3.jpg", 'alt' => "Crowd going wild at a Jeff Bone set"),
        array('id' => 4, 'src' => "assets/img/gallery-4.jpg", 'alt' => "Studio session with Jeff Bone"),
        array('id' => 5, 'src' => "assets/img/gallery-5.jpg", 'alt' => "Jeff Bone at Ibiza Closing Party"),
        array('id' => 6, 'src' => "assets/img/gallery-6.jpg", 'alt' => "Backstage with Jeff Bone")
    ),
    'contact' => array(
        'email' => "booking@jeffbone.com",
        'phone' => "+1 (555) 123-4567",
        'socials' => array(
            'instagram' => "https://www.instagram.com/jeffbone",
            'facebook' => "https://www.facebook.com/jeffbone",
            'twitter' => "https://www.twitter.com/jeffbone",
            'soundcloud' => "https://www.soundcloud.com/jeffbone"
        )
    )
);

// Initialize data with default values
$data = $default_data;

// Check if data file exists, if not create it with default data
if (!file_exists($data_file)) {
    if (!@file_put_contents($data_file, json_encode($default_data, JSON_PRETTY_PRINT))) {
        $error_message = "Unable to create data file. Please check permissions.";
        error_log($error_message);
    } else {
        // File was successfully created
        $success_message = "Initial data file was created successfully.";
        error_log($success_message);
        chmod($data_file, 0644);
    }
}

// Try to load data from the JSON file if it exists
if (file_exists($data_file)) {
    $file_content = @file_get_contents($data_file);
    if ($file_content !== false) {
        $json_data = json_decode($file_content, true);
        // Only use the JSON data if it's valid
        if (json_last_error() === JSON_ERROR_NONE && is_array($json_data)) {
            error_log("Successfully loaded data from: " . $data_file);
            $data = $json_data;
            
            // Ensure required structures exist
            if (!isset($data['about']['stats'])) {
                $data['about']['stats'] = $default_data['about']['stats'];
            }
            
            if (!isset($data['about']['stats_titles'])) {
                $data['about']['stats_titles'] = $default_data['about']['stats_titles'];
            }
            
            if (!isset($data['videos'])) {
                $data['videos'] = $default_data['videos'];
            }
            
            if (!isset($data['hero'])) {
                $data['hero'] = $default_data['hero'];
            }
        } else {
            $error_message = "Invalid JSON data in " . $data_file . ": " . json_last_error_msg() . ". Using default values.";
            error_log($error_message);
        }
    } else {
        $error_message = "Unable to read data file: " . $data_file . ". Using default values.";
        error_log($error_message);
    }
}

// Function to format date string
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('l, F j, Y');
}

// Check if user is requesting admin page
$showAdmin = isset($_GET['admin']) && $_GET['admin'] == 'true';
// Check if user is authenticated
$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Display message from session if it exists
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    // Clear the message after displaying it
    unset($_SESSION['message']);
    error_log("Session message displayed: " . $message['text'] . " (Type: " . $message['type'] . ")");
}

// For debugging admin authentication
if ($showAdmin) {
    error_log("Admin page requested. Authentication status: " . ($isAuthenticated ? 'Authenticated' : 'Not authenticated'));
}

// Prevent output buffering issues
ob_start();

// Page structure
if ($showAdmin) {
    if ($isAuthenticated) {
        include 'admin_panel.php';
    } else {
        include 'admin_login.php';
    }
} else {
    include 'templates/header.php';
    include 'templates/hero.php';
    include 'templates/about.php';
    include 'templates/events.php';
    include 'templates/music.php';
    include 'templates/videos.php';
    include 'templates/gallery.php';
    include 'newsbuzz/index.php';
    include 'templates/contact.php';
    include 'templates/footer.php';
}

ob_end_flush();
?>