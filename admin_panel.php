<?php
// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: index.php?admin=true');
    exit;
}

// Load CSS and JS files
$css_files = [
    'assets/css/styles.css',
    'assets/css/admin.css'
];

$js_files = [
    'assets/js/admin.js'
];

// Include various JavaScript code blocks
$js_includes = ''; // JavaScript will be added here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DJ Jeff Bone - Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Files -->
    <?php foreach ($css_files as $file): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($file); ?>">
    <?php endforeach; ?>
    
    <style>
        :root {
            --bg-dark: #0F0015; 
            --bg-darker: #080008;
            --card-bg: rgba(0, 0, 0, 0.6);
            --primary-color: #8B5CF6;
            --primary-hover: #7C3AED;
            --border-color: rgba(139, 92, 246, 0.3);
            --text-muted: #94a3b8;
            --transition: all 0.3s ease;
        }
        
        .aspect-w-16 {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        }
        
        .aspect-w-16 iframe,
        .aspect-w-16 > div {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        
        /* Image preview styles */
        .image-preview {
            width: 100%;
            height: 200px;
            background-color: #0F0015;
            border: 1px dashed var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            width: 100%;
            height: 100%;
            display: none;
        }
        
        .delete-image-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
        }
        
        /* Toast notification styles */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }
        
        .toast {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease;
        }
        
        .toast.success {
            background-color: #064e3b;
            color: #fff;
        }
        
        .toast.error {
            background-color: #7f1d1d;
            color: #fff;
        }
        
        .toast i {
            margin-right: 0.75rem;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="admin-page">
    <!-- Toast notification container -->
    <div id="toastContainer" class="toast-container"></div>
    
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container mx-auto px-4">
            <div class="admin-header-content">
                <h1>DJ Jeff Bone - Admin Panel</h1>
                <a href="index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Back to Site</span>
                </a>
            </div>
        </div>
    </header>
    
    <!-- Admin Content -->
    <div class="admin-content">
        <div class="container mx-auto px-4">
            <?php if (isset($message)): ?>
                <div class="message <?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>
            
            <form id="adminForm" action="save_data.php" method="POST" enctype="multipart/form-data" class="admin-tabs">
                <!-- Tabs Navigation -->
                <div class="tabs-nav">
                    <button type="button" class="tab-btn active" data-tab="hero">Hero</button>
                    <button type="button" class="tab-btn" data-tab="about">About</button>
                    <button type="button" class="tab-btn" data-tab="events">Events</button>
                    <button type="button" class="tab-btn" data-tab="music">Music</button>
                    <button type="button" class="tab-btn" data-tab="videos">Videos</button>
                    <button type="button" class="tab-btn" data-tab="gallery">Gallery</button>
                    <button type="button" class="tab-btn" data-tab="contact">Contact</button>
                </div>
                
                <div class="tabs-content">
                    <!-- Hero Tab -->
                    <div id="heroTab" class="tab-pane active">
                        <h2>Hero Section</h2>
                        
                        <div class="form-group">
                            <label for="heroTitle">Title</label>
                            <input type="text" id="heroTitle" name="hero[title]" value="<?php echo htmlspecialchars(isset($data['hero']['title']) ? $data['hero']['title'] : 'DJ JEFF BONE'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="heroTagline">Tagline</label>
                            <input type="text" id="heroTagline" name="hero[tagline]" value="<?php echo htmlspecialchars(isset($data['hero']['tagline']) ? $data['hero']['tagline'] : 'Electronic Music Artist'); ?>">
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="heroButton1Text">Button 1 Text</label>
                                <input type="text" id="heroButton1Text" name="hero[button1_text]" value="<?php echo htmlspecialchars(isset($data['hero']['button1_text']) ? $data['hero']['button1_text'] : 'Listen Now'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="heroButton1Link">Button 1 Link</label>
                                <input type="text" id="heroButton1Link" name="hero[button1_link]" value="<?php echo htmlspecialchars(isset($data['hero']['button1_link']) ? $data['hero']['button1_link'] : '#music'); ?>">
                                <p class="helper-text">Use # followed by section ID (e.g. #music) or a full URL</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="heroButton2Text">Button 2 Text</label>
                                <input type="text" id="heroButton2Text" name="hero[button2_text]" value="<?php echo htmlspecialchars(isset($data['hero']['button2_text']) ? $data['hero']['button2_text'] : 'Upcoming Shows'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="heroButton2Link">Button 2 Link</label>
                                <input type="text" id="heroButton2Link" name="hero[button2_link]" value="<?php echo htmlspecialchars(isset($data['hero']['button2_link']) ? $data['hero']['button2_link'] : '#events'); ?>">
                                <p class="helper-text">Use # followed by section ID (e.g. #events) or a full URL</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Background Type</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="hero[background_type]" value="image" <?php echo (!isset($data['hero']['background_type']) || $data['hero']['background_type'] === 'image') ? 'checked' : ''; ?>>
                                    Image
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="hero[background_type]" value="video" <?php echo (isset($data['hero']['background_type']) && $data['hero']['background_type'] === 'video') ? 'checked' : ''; ?>>
                                    Video
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group hero-background-image-group" style="<?php echo (isset($data['hero']['background_type']) && $data['hero']['background_type'] === 'video') ? 'display: none;' : ''; ?>">
                            <label>Background Image</label>
                            <div class="image-preview">
                                <img 
                                    id="heroImagePreview" 
                                    src="<?php echo !empty($data['hero']['background_image']) ? htmlspecialchars($data['hero']['background_image']) : 'assets/img/placeholder.jpg'; ?>" 
                                    style="display: <?php echo !empty($data['hero']['background_image']) ? 'block' : 'none'; ?>;"
                                >
                                <button type="button" class="delete-image-btn" data-preview="heroImagePreview" data-input="heroImageUpload" data-hidden="deleteHeroImage">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <input type="file" id="heroImageUpload" name="hero_image" class="image-upload" data-preview="heroImagePreview">
                            <input type="hidden" id="deleteHeroImage" name="delete_hero_image" value="0">
                            <input type="hidden" name="hero[background_image]" value="<?php echo htmlspecialchars(isset($data['hero']['background_image']) ? $data['hero']['background_image'] : ''); ?>">
                            <p class="helper-text">Recommended: 1920x1080px or larger, 16:9 aspect ratio</p>
                        </div>
                        
                        <div class="form-group hero-background-video-group" style="<?php echo (!isset($data['hero']['background_type']) || $data['hero']['background_type'] !== 'video') ? 'display: none;' : ''; ?>">
                            <label for="heroVideoUpload">Background Video</label>
                            <div class="video-file-input">
                                <input type="file" id="heroVideoUpload" name="hero_video" accept="video/mp4">
                                <?php if (isset($data['hero']['background_video']) && !empty($data['hero']['background_video'])): ?>
                                    <p class="current-video-name">Current: <?php echo htmlspecialchars(basename($data['hero']['background_video'])); ?></p>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="delete_hero_video" value="1">
                                        Delete current video
                                    </label>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="hero[background_video]" value="<?php echo htmlspecialchars(isset($data['hero']['background_video']) ? $data['hero']['background_video'] : ''); ?>">
                            <p class="helper-text">MP4 format only. Recommended: 1920x1080px, 16:9 aspect ratio, max 10MB</p>
                        </div>
                    </div>
                    
                    <!-- About Tab -->
                    <div id="aboutTab" class="tab-pane">
                        <h2>About Information</h2>
                        
                        <div class="form-group">
                            <label for="aboutTitle">Title</label>
                            <input type="text" id="aboutTitle" name="about[title]" value="<?php echo htmlspecialchars(isset($data['about']['title']) ? $data['about']['title'] : ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="aboutDescription">Description</label>
                            <textarea id="aboutDescription" name="about[description]" rows="5"><?php echo htmlspecialchars(isset($data['about']['description']) ? $data['about']['description'] : ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Profile Image</label>
                            <div class="image-preview">
                                <img 
                                    id="aboutImagePreview" 
                                    src="<?php echo !empty($data['about']['image']) ? htmlspecialchars($data['about']['image']) : 'assets/img/placeholder.jpg'; ?>" 
                                    style="display: <?php echo !empty($data['about']['image']) ? 'block' : 'none'; ?>;"
                                >
                                <button type="button" class="delete-image-btn" data-preview="aboutImagePreview" data-input="aboutImageUpload" data-hidden="deleteAboutImage">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <input type="file" id="aboutImageUpload" name="about_image" class="image-upload" data-preview="aboutImagePreview">
                            <input type="hidden" id="deleteAboutImage" name="delete_about_image" value="0">
                            <input type="hidden" name="about[image]" value="<?php echo htmlspecialchars(isset($data['about']['image']) ? $data['about']['image'] : ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <h3>Statistics</h3>
                            <div class="form-grid">
                                <div>
                                    <label for="statsYearsTitle">Years Title</label>
                                    <input type="text" id="statsYearsTitle" name="about[stats_titles][years]" value="<?php echo htmlspecialchars(isset($data['about']['stats_titles']['years']) ? $data['about']['stats_titles']['years'] : 'Years Experience'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsYears">Years Value</label>
                                    <input type="text" id="statsYears" name="about[stats][years]" value="<?php echo htmlspecialchars(isset($data['about']['stats']['years']) ? $data['about']['stats']['years'] : '10+'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsShowsTitle">Shows Title</label>
                                    <input type="text" id="statsShowsTitle" name="about[stats_titles][shows]" value="<?php echo htmlspecialchars(isset($data['about']['stats_titles']['shows']) ? $data['about']['stats_titles']['shows'] : 'Shows'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsShows">Shows Value</label>
                                    <input type="text" id="statsShows" name="about[stats][shows]" value="<?php echo htmlspecialchars(isset($data['about']['stats']['shows']) ? $data['about']['stats']['shows'] : '500+'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsCountriesTitle">Countries Title</label>
                                    <input type="text" id="statsCountriesTitle" name="about[stats_titles][countries]" value="<?php echo htmlspecialchars(isset($data['about']['stats_titles']['countries']) ? $data['about']['stats_titles']['countries'] : 'Countries'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsCountries">Countries Value</label>
                                    <input type="text" id="statsCountries" name="about[stats][countries]" value="<?php echo htmlspecialchars(isset($data['about']['stats']['countries']) ? $data['about']['stats']['countries'] : '20+'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsFestivalsTitle">Festivals Title</label>
                                    <input type="text" id="statsFestivalsTitle" name="about[stats_titles][festivals]" value="<?php echo htmlspecialchars(isset($data['about']['stats_titles']['festivals']) ? $data['about']['stats_titles']['festivals'] : 'Festivals'); ?>">
                                </div>
                                
                                <div>
                                    <label for="statsFestivals">Festivals Value</label>
                                    <input type="text" id="statsFestivals" name="about[stats][festivals]" value="<?php echo htmlspecialchars(isset($data['about']['stats']['festivals']) ? $data['about']['stats']['festivals'] : '50+'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Events Tab -->
                    <div id="eventsTab" class="tab-pane">
                        <div class="tab-header">
                            <h2>Upcoming Events</h2>
                            <button type="button" id="addEventBtn" class="btn btn-outline add-item-btn">
                                <i class="fas fa-plus"></i> Add Event
                            </button>
                        </div>
                        
                        <div id="eventsContainer">
                            <?php if (empty($data['events'])): ?>
                                <div class="no-items-message">
                                    <p>No events added yet. Click "Add Event" to create your first event.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['events'] as $index => $event): ?>
                                    <div class="item-card" data-index="<?php echo $index; ?>">
                                        <button type="button" class="remove-item-btn" data-index="<?php echo $index; ?>" data-remove="event">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <input type="hidden" name="events[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($event['id']); ?>">
                                        
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label for="eventDate<?php echo $index; ?>">Date</label>
                                                <input type="date" id="eventDate<?php echo $index; ?>" name="events[<?php echo $index; ?>][date]" value="<?php echo htmlspecialchars($event['date']); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="eventVenue<?php echo $index; ?>">Venue</label>
                                                <input type="text" id="eventVenue<?php echo $index; ?>" name="events[<?php echo $index; ?>][venue]" value="<?php echo htmlspecialchars($event['venue']); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="eventLocation<?php echo $index; ?>">Location</label>
                                                <input type="text" id="eventLocation<?php echo $index; ?>" name="events[<?php echo $index; ?>][location]" value="<?php echo htmlspecialchars($event['location']); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="eventTicket<?php echo $index; ?>">Ticket Link (Optional)</label>
                                                <input type="text" id="eventTicket<?php echo $index; ?>" name="events[<?php echo $index; ?>][ticketLink]" value="<?php echo htmlspecialchars(isset($event['ticketLink']) ? $event['ticketLink'] : ''); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Event Image (Optional)</label>
                                            <div class="image-preview">
                                                <img 
                                                    id="eventImagePreview<?php echo $index; ?>" 
                                                    src="<?php echo !empty($event['image']) ? htmlspecialchars($event['image']) : 'assets/img/placeholder.jpg'; ?>" 
                                                    style="display: <?php echo !empty($event['image']) ? 'block' : 'none'; ?>;"
                                                >
                                                <button type="button" class="delete-image-btn" data-preview="eventImagePreview<?php echo $index; ?>" data-input="eventImageUpload<?php echo $index; ?>" data-hidden="deleteEventImage<?php echo $index; ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                            <input type="file" id="eventImageUpload<?php echo $index; ?>" name="event_image_<?php echo $index; ?>" class="image-upload" data-preview="eventImagePreview<?php echo $index; ?>">
                                            <input type="hidden" id="deleteEventImage<?php echo $index; ?>" name="delete_event_image_<?php echo $index; ?>" value="0">
                                            <input type="hidden" name="events[<?php echo $index; ?>][image]" value="<?php echo htmlspecialchars(isset($event['image']) ? $event['image'] : ''); ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Music Tab -->
                    <div id="musicTab" class="tab-pane">
                        <div class="tab-header">
                            <h2>Music Tracks</h2>
                            <button type="button" id="addTrackBtn" class="btn btn-outline add-item-btn">
                                <i class="fas fa-plus"></i> Add Track
                            </button>
                        </div>
                        
                        <div id="tracksContainer">
                            <?php if (empty($data['music'])): ?>
                                <div class="no-items-message">
                                    <p>No tracks added yet. Click "Add Track" to add your first track.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['music'] as $index => $track): ?>
                                    <div class="item-card" data-index="<?php echo $index; ?>">
                                        <button type="button" class="remove-item-btn" data-index="<?php echo $index; ?>" data-remove="track">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <input type="hidden" name="music[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($track['id']); ?>">
                                        
                                        <div class="form-group">
                                            <label for="trackTitle<?php echo $index; ?>">Track Title</label>
                                            <input type="text" id="trackTitle<?php echo $index; ?>" name="music[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($track['title']); ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="trackEmbed<?php echo $index; ?>">SoundCloud Embed URL</label>
                                            <input type="text" id="trackEmbed<?php echo $index; ?>" name="music[<?php echo $index; ?>][embed]" value="<?php echo htmlspecialchars($track['embed']); ?>">
                                            <p class="helper-text">Paste the SoundCloud embed URL here</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Videos Tab -->
                    <div id="videosTab" class="tab-pane">
                        <div class="tab-header">
                            <h2>Videos</h2>
                            <button type="button" id="addVideoBtn" class="btn btn-outline add-item-btn">
                                <i class="fas fa-plus"></i> Add Video
                            </button>
                        </div>
                        
                        <div id="videosContainer">
                            <?php if (empty($data['videos'])): ?>
                                <div class="no-items-message">
                                    <p>No videos added yet. Click "Add Video" to add your first video.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['videos'] as $index => $video): ?>
                                    <div class="item-card" data-index="<?php echo $index; ?>">
                                        <button type="button" class="remove-item-btn" data-index="<?php echo $index; ?>" data-remove="video">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <input type="hidden" name="videos[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars(isset($video['id']) ? $video['id'] : ''); ?>">
                                        
                                        <div class="form-group">
                                            <label for="videoTitle<?php echo $index; ?>">Video Title</label>
                                            <input type="text" id="videoTitle<?php echo $index; ?>" name="videos[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(isset($video['title']) ? $video['title'] : 'Untitled Video'); ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="videoEmbed<?php echo $index; ?>">YouTube URL</label>
                                            <input type="text" id="videoEmbed<?php echo $index; ?>" name="videos[<?php echo $index; ?>][embed]" value="<?php echo htmlspecialchars(isset($video['embed']) ? $video['embed'] : ''); ?>">
                                            <p class="helper-text">Enter the YouTube video URL (e.g., https://www.youtube.com/watch?v=XXXXXXXXXXX)</p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="videoDescription<?php echo $index; ?>">Description (Optional)</label>
                                            <textarea id="videoDescription<?php echo $index; ?>" name="videos[<?php echo $index; ?>][description]" rows="3"><?php echo htmlspecialchars(isset($video['description']) ? $video['description'] : ''); ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Gallery Tab -->
                    <div id="galleryTab" class="tab-pane">
                        <div class="tab-header">
                            <h2>Gallery Images</h2>
                            <button type="button" id="addImageBtn" class="btn btn-outline add-item-btn">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                        </div>
                        
                        <div id="galleryContainer" class="gallery-edit-grid">
                            <?php if (empty($data['gallery'])): ?>
                                <div class="no-items-message">
                                    <p>No images added yet. Click "Add Image" to add your first image.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['gallery'] as $index => $image): ?>
                                    <div class="item-card gallery-item-card" data-index="<?php echo $index; ?>">
                                        <button type="button" class="remove-item-btn" data-index="<?php echo $index; ?>" data-remove="image">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <input type="hidden" name="gallery[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($image['id']); ?>">
                                        
                                        <div class="image-preview">
                                            <img 
                                                id="galleryImagePreview<?php echo $index; ?>" 
                                                src="<?php echo !empty($image['src']) ? htmlspecialchars($image['src']) : 'assets/img/placeholder.jpg'; ?>" 
                                                style="display: <?php echo !empty($image['src']) ? 'block' : 'none'; ?>;"
                                            >
                                            <button type="button" class="delete-image-btn" data-preview="galleryImagePreview<?php echo $index; ?>" data-input="galleryImageUpload<?php echo $index; ?>" data-hidden="deleteGalleryImage<?php echo $index; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="galleryImageUpload<?php echo $index; ?>">Image</label>
                                            <input type="file" id="galleryImageUpload<?php echo $index; ?>" name="gallery_image_<?php echo $index; ?>" class="image-upload" data-preview="galleryImagePreview<?php echo $index; ?>">
                                            <input type="hidden" id="deleteGalleryImage<?php echo $index; ?>" name="delete_gallery_image_<?php echo $index; ?>" value="0">
                                            <input type="hidden" name="gallery[<?php echo $index; ?>][src]" value="<?php echo htmlspecialchars(isset($image['src']) ? $image['src'] : ''); ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="galleryImageAlt<?php echo $index; ?>">Alt Text</label>
                                            <input type="text" id="galleryImageAlt<?php echo $index; ?>" name="gallery[<?php echo $index; ?>][alt]" value="<?php echo htmlspecialchars(isset($image['alt']) ? $image['alt'] : ''); ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Contact Tab -->
                    <div id="contactTab" class="tab-pane">
                        <h2>Contact Information</h2>
                        
                        <div class="form-group">
                            <label for="contactEmail">Email</label>
                            <input type="email" id="contactEmail" name="contact[email]" value="<?php echo htmlspecialchars(isset($data['contact']['email']) ? $data['contact']['email'] : ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contactPhone">Phone</label>
                            <input type="text" id="contactPhone" name="contact[phone]" value="<?php echo htmlspecialchars(isset($data['contact']['phone']) ? $data['contact']['phone'] : ''); ?>">
                        </div>
                        
                        <div class="social-media-section">
                            <h3>Social Media Links</h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="socialInstagram">Instagram URL</label>
                                    <input type="text" id="socialInstagram" name="contact[socials][instagram]" value="<?php echo htmlspecialchars(isset($data['contact']['socials']['instagram']) ? $data['contact']['socials']['instagram'] : ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="socialFacebook">Facebook URL</label>
                                    <input type="text" id="socialFacebook" name="contact[socials][facebook]" value="<?php echo htmlspecialchars(isset($data['contact']['socials']['facebook']) ? $data['contact']['socials']['facebook'] : ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="socialTwitter">Twitter URL</label>
                                    <input type="text" id="socialTwitter" name="contact[socials][twitter]" value="<?php echo htmlspecialchars(isset($data['contact']['socials']['twitter']) ? $data['contact']['socials']['twitter'] : ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="socialSoundCloud">SoundCloud URL</label>
                                    <input type="text" id="socialSoundCloud" name="contact[socials][soundcloud]" value="<?php echo htmlspecialchars(isset($data['contact']['socials']['soundcloud']) ? $data['contact']['socials']['soundcloud'] : ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary save-btn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Template for new events -->
    <template id="eventTemplate">
        <div class="item-card" data-index="{{index}}">
            <button type="button" class="remove-item-btn" data-index="{{index}}" data-remove="event">
                <i class="fas fa-times"></i>
            </button>
            
            <input type="hidden" name="events[{{index}}][id]" value="{{id}}">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="eventDate{{index}}">Date</label>
                    <input type="date" id="eventDate{{index}}" name="events[{{index}}][date]" value="">
                </div>
                
                <div class="form-group">
                    <label for="eventVenue{{index}}">Venue</label>
                    <input type="text" id="eventVenue{{index}}" name="events[{{index}}][venue]" value="">
                </div>
                
                <div class="form-group">
                    <label for="eventLocation{{index}}">Location</label>
                    <input type="text" id="eventLocation{{index}}" name="events[{{index}}][location]" value="">
                </div>
                
                <div class="form-group">
                    <label for="eventTicket{{index}}">Ticket Link (Optional)</label>
                    <input type="text" id="eventTicket{{index}}" name="events[{{index}}][ticketLink]" value="">
                </div>
            </div>
            
            <div class="form-group">
                <label>Event Image (Optional)</label>
                <div class="image-preview">
                    <img id="eventImagePreview{{index}}" src="assets/img/placeholder.jpg">
                    <button type="button" class="delete-image-btn" data-preview="eventImagePreview{{index}}" data-input="eventImageUpload{{index}}" data-hidden="deleteEventImage{{index}}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <input type="file" id="eventImageUpload{{index}}" name="event_image_{{index}}" class="image-upload" data-preview="eventImagePreview{{index}}">
                <input type="hidden" id="deleteEventImage{{index}}" name="delete_event_image_{{index}}" value="0">
                <input type="hidden" name="events[{{index}}][image]" value="">
            </div>
        </div>
    </template>
    
    <!-- Template for new tracks -->
    <template id="trackTemplate">
        <div class="item-card" data-index="{{index}}">
            <button type="button" class="remove-item-btn" data-index="{{index}}" data-remove="track">
                <i class="fas fa-times"></i>
            </button>
            
            <input type="hidden" name="music[{{index}}][id]" value="{{id}}">
            
            <div class="form-group">
                <label for="trackTitle{{index}}">Track Title</label>
                <input type="text" id="trackTitle{{index}}" name="music[{{index}}][title]" value="">
            </div>
            
            <div class="form-group">
                <label for="trackEmbed{{index}}">SoundCloud Embed URL</label>
                <input type="text" id="trackEmbed{{index}}" name="music[{{index}}][embed]" value="">
                <p class="helper-text">Paste the SoundCloud embed URL here</p>
            </div>
        </div>
    </template>
    
    <!-- Template for new videos -->
    <template id="videoTemplate">
        <div class="item-card" data-index="{{index}}">
            <button type="button" class="remove-item-btn" data-index="{{index}}" data-remove="video">
                <i class="fas fa-times"></i>
            </button>
            
            <input type="hidden" name="videos[{{index}}][id]" value="{{id}}">
            
            <div class="form-group">
                <label for="videoTitle{{index}}">Video Title</label>
                <input type="text" id="videoTitle{{index}}" name="videos[{{index}}][title]" value="">
            </div>
            
            <div class="form-group">
                <label for="videoEmbed{{index}}">YouTube URL</label>
                <input type="text" id="videoEmbed{{index}}" name="videos[{{index}}][embed]" value="">
                <p class="helper-text">Enter the YouTube video URL (e.g., https://www.youtube.com/watch?v=XXXXXXXXXXX)</p>
            </div>
            
            <div class="form-group">
                <label for="videoDescription{{index}}">Description (Optional)</label>
                <textarea id="videoDescription{{index}}" name="videos[{{index}}][description]" rows="3"></textarea>
            </div>
        </div>
    </template>
    
    <!-- Template for new gallery images -->
    <template id="imageTemplate">
        <div class="item-card gallery-item-card" data-index="{{index}}">
            <button type="button" class="remove-item-btn" data-index="{{index}}" data-remove="image">
                <i class="fas fa-times"></i>
            </button>
            
            <input type="hidden" name="gallery[{{index}}][id]" value="{{id}}">
            
            <div class="image-preview">
                <img id="galleryImagePreview{{index}}" src="assets/img/placeholder.jpg">
                <button type="button" class="delete-image-btn" data-preview="galleryImagePreview{{index}}" data-input="galleryImageUpload{{index}}" data-hidden="deleteGalleryImage{{index}}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="form-group">
                <label for="galleryImageUpload{{index}}">Image</label>
                <input type="file" id="galleryImageUpload{{index}}" name="gallery_image_{{index}}" class="image-upload" data-preview="galleryImagePreview{{index}}">
                <input type="hidden" id="deleteGalleryImage{{index}}" name="delete_gallery_image_{{index}}" value="0">
                <input type="hidden" name="gallery[{{index}}][src]" value="">
            </div>
            
            <div class="form-group">
                <label for="galleryImageAlt{{index}}">Alt Text</label>
                <input type="text" id="galleryImageAlt{{index}}" name="gallery[{{index}}][alt]" value="">
            </div>
        </div>
    </template>
    
    <!-- PHP will insert session message JavaScript here if needed -->
    <?php if (isset($message)): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const phpMessage = <?php echo json_encode($message['text']); ?>;
        const phpMessageType = <?php echo json_encode($message['type']); ?>;
        
        // Show toast notification
        showToast(phpMessage, phpMessageType);
    });
    </script>
    <?php endif; ?>
    
    <!-- JavaScript Files -->
    <?php foreach ($js_files as $file): ?>
        <script src="<?php echo htmlspecialchars($file); ?>"></script>
    <?php endforeach; ?>
    
    <!-- JavaScript for handling new video entries -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addVideoBtn = document.getElementById('addVideoBtn');
        const videosContainer = document.getElementById('videosContainer');
        
        if (addVideoBtn && videosContainer) {
            addVideoBtn.addEventListener('click', () => {
                // Get the template
                const template = document.getElementById('videoTemplate');
                if (!template) return;
                
                // Remove no items message if present
                const noItemsMessage = videosContainer.querySelector('.no-items-message');
                if (noItemsMessage) {
                    noItemsMessage.remove();
                }
                
                // Generate new ID and index
                const id = Date.now();
                const existingItems = videosContainer.querySelectorAll('.item-card');
                const index = existingItems.length;
                
                // Create new item from template
                const templateContent = template.innerHTML
                    .replace(/{{index}}/g, index.toString())
                    .replace(/{{id}}/g, id.toString());
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = templateContent;
                const newItem = tempDiv.firstElementChild;
                
                // Add to container
                videosContainer.appendChild(newItem);
                
                // Add event listener for remove button
                setupRemoveButton(newItem.querySelector('.remove-item-btn'));
            });
        }
    });
    </script>
    
    <!-- JavaScript for handling Hero background type toggle -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle background type toggle
        const backgroundTypeRadios = document.querySelectorAll('input[name="hero[background_type]"]');
        const imageGroup = document.querySelector('.hero-background-image-group');
        const videoGroup = document.querySelector('.hero-background-video-group');
        
        if (backgroundTypeRadios && imageGroup && videoGroup) {
            backgroundTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'image') {
                        imageGroup.style.display = 'block';
                        videoGroup.style.display = 'none';
                    } else {
                        imageGroup.style.display = 'none';
                        videoGroup.style.display = 'block';
                    }
                });
            });
        }
    });
    </script>
    
    <?php echo $js_includes; ?>
</body>
</html>
