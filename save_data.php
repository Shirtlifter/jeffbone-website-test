<?php
// Start session at the very beginning
session_start();

// Enable detailed error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'You must be logged in to perform this action.'
    );
    header('Location: index.php?admin=true');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define data directory and file path with absolute path
    $data_dir = __DIR__ . '/data';
    $uploads_dir = __DIR__ . '/assets/img/uploads';
    $data_file = $data_dir . '/site_data.json';
    
    // Log the directory and file paths for debugging
    error_log("Data directory path: " . $data_dir);
    error_log("Uploads directory path: " . $uploads_dir);
    error_log("Data file path: " . $data_file);
    
    // Check if directories exist before attempting to create
    $directories_ok = true;
    
    // Create necessary directories if they don't exist
    foreach ([$data_dir, $uploads_dir] as $dir) {
        if (!file_exists($dir)) {
            error_log("Directory doesn't exist. Attempting to create: " . $dir);
            
            // Try to create directory but handle errors gracefully
            if (!@mkdir($dir, 0755, true)) {
                $error = error_get_last();
                error_log("Failed to create directory: " . $error['message']);
                
                // Check if parent directory is writable
                $parent_dir = dirname($dir);
                if (!is_writable($parent_dir)) {
                    error_log("Parent directory is not writable: " . $parent_dir);
                    error_log("Parent directory permissions: " . substr(sprintf('%o', fileperms($parent_dir)), -4));
                    
                    $_SESSION['message'] = array(
                        'type' => 'error',
                        'text' => 'Permission denied: Server cannot create required directories. Please contact your server administrator to ensure the web server has write permissions to ' . $parent_dir
                    );
                    $directories_ok = false;
                } else {
                    $_SESSION['message'] = array(
                        'type' => 'error',
                        'text' => 'Failed to create directory. Error: ' . $error['message']
                    );
                    $directories_ok = false;
                }
            } else {
                error_log("Successfully created directory with permissions 0755");
                chmod($dir, 0755);
            }
        }
    }
    
    // If directories couldn't be created, redirect back to admin
    if (!$directories_ok) {
        header('Location: index.php?admin=true');
        exit;
    }
    
    // Check if directories are writable
    foreach ([$data_dir, $uploads_dir] as $dir) {
        if (!is_writable($dir)) {
            error_log("Directory is not writable: " . $dir);
            error_log("Directory permissions: " . substr(sprintf('%o', fileperms($dir)), -4));
            
            $_SESSION['message'] = array(
                'type' => 'error',
                'text' => 'Directory is not writable: ' . $dir . '. Please ask your server administrator to set permissions to 755 or 775.'
            );
            header('Location: index.php?admin=true');
            exit;
        }
    }
    
    // Function to handle file uploads - modified to preserve original filenames
    function handleFileUpload($file_key, $delete_key, $old_path) {
        global $uploads_dir;
        
        // Check if deletion is requested
        if (isset($_POST[$delete_key]) && $_POST[$delete_key] == '1') {
            // If file exists and is within our uploads directory, delete it
            if (!empty($old_path) && file_exists($_SERVER['DOCUMENT_ROOT'] . $old_path) && strpos($old_path, 'uploads') !== false) {
                @unlink($_SERVER['DOCUMENT_ROOT'] . $old_path);
                error_log("Deleted file: " . $_SERVER['DOCUMENT_ROOT'] . $old_path);
            } elseif (!empty($old_path) && file_exists($old_path)) {
                // Try direct path
                @unlink($old_path);
                error_log("Deleted file using direct path: " . $old_path);
            }
            return '';
        }
        
        // Check if a file was uploaded
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES[$file_key]['tmp_name'];
            $name = basename($_FILES[$file_key]['name']);
            $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            // Sanitize the filename: remove special characters and spaces
            $base_name = pathinfo($name, PATHINFO_FILENAME);
            $base_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $base_name);
            $base_name = trim($base_name);
            
            // If filename is empty after sanitizing, use a timestamp
            if (empty($base_name)) {
                $base_name = 'image_' . time();
            }
            
            // Create filename with extension
            $new_name = $base_name . '.' . $file_ext;
            
            // Check if file already exists, if so, append a counter
            $counter = 1;
            $original_name = $new_name;
            while (file_exists($uploads_dir . '/' . $new_name)) {
                $new_name = $base_name . '_' . $counter . '.' . $file_ext;
                $counter++;
                // Prevent infinite loops by setting a limit
                if ($counter > 1000) {
                    $new_name = uniqid('img_') . '.' . $file_ext;
                    break;
                }
            }
            
            $upload_path = $uploads_dir . '/' . $new_name;
            // Store path without leading slash for proper relative paths
            $web_path = 'assets/img/uploads/' . $new_name;
            
            // Log paths for debugging
            error_log("File upload - Original filename: " . $name);
            error_log("File upload - Sanitized filename: " . $new_name);
            error_log("File upload - Upload path: " . $upload_path);
            error_log("File upload - Web path: " . $web_path);
            
            // Check file type
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_ext, $allowed_extensions)) {
                error_log("Invalid file type for {$file_key}: {$file_ext}");
                return $old_path;
            }
            
            // Move the uploaded file to the destination
            if (move_uploaded_file($tmp_name, $upload_path)) {
                error_log("File uploaded successfully: {$web_path}");
                chmod($upload_path, 0644); // Make file readable
                
                // If there's an old file, delete it
                if (!empty($old_path) && file_exists($_SERVER['DOCUMENT_ROOT'] . $old_path) && strpos($old_path, 'uploads') !== false) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $old_path);
                    error_log("Deleted old file: " . $_SERVER['DOCUMENT_ROOT'] . $old_path);
                } elseif (!empty($old_path) && file_exists($old_path)) {
                    // Try direct path
                    @unlink($old_path);
                    error_log("Deleted old file using direct path: " . $old_path);
                }
                
                return $web_path;
            } else {
                error_log("Failed to move uploaded file for {$file_key}");
                error_log("Temp file: {$tmp_name}, Upload path: {$upload_path}");
                error_log("Upload directory writable: " . (is_writable($uploads_dir) ? 'Yes' : 'No'));
                error_log("File exists after upload attempt: " . (file_exists($upload_path) ? 'Yes' : 'No'));
                
                return $old_path;
            }
        }
        
        // No new file uploaded, return old path
        return $old_path;
    }
    
    // Process the form data
    $new_data = array();
    
    // Debug - check POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    // Process Hero section
    if (isset($_POST['hero'])) {
        $new_data['hero'] = $_POST['hero'];
        
        // Handle background image upload if background type is image
        if (isset($new_data['hero']['background_type']) && $new_data['hero']['background_type'] === 'image') {
            $old_image_path = isset($new_data['hero']['background_image']) ? $new_data['hero']['background_image'] : '';
            $new_image_path = handleFileUpload('hero_background_image', 'delete_hero_background_image', $old_image_path);
            $new_data['hero']['background_image'] = $new_image_path;
            error_log("Hero background image path saved as: " . $new_image_path);
        }
        
        // Handle background video upload if background type is video
        if (isset($new_data['hero']['background_type']) && $new_data['hero']['background_type'] === 'video') {
            $old_video_path = isset($new_data['hero']['background_video']) ? $new_data['hero']['background_video'] : '';
            // Note: we're not handling video uploads in this function, just keeping the path if it exists
            // You would need to extend handleFileUpload to handle video files if needed
            $new_data['hero']['background_video'] = $old_video_path;
        }
    } else {
        // Default values if not set
        $new_data['hero'] = array(
            'title' => 'DJ JEFF BONE',
            'tagline' => 'Electronic Music Artist',
            'button1_text' => 'Listen Now',
            'button1_link' => '#music',
            'button2_text' => 'Upcoming Shows',
            'button2_link' => '#events',
            'background_type' => 'image',
            'background_image' => 'assets/img/hero-bg.jpg',
            'background_video' => ''
        );
        error_log("Hero section not found in POST data, using default");
    }
    
    // Process About section
    if (isset($_POST['about'])) {
        $new_data['about'] = $_POST['about'];
        
        // Handle about image upload
        if (isset($new_data['about']['image'])) {
            $old_image_path = $new_data['about']['image'];
            $new_image_path = handleFileUpload('about_image', 'delete_about_image', $old_image_path);
            $new_data['about']['image'] = $new_image_path;
            error_log("About image path saved as: " . $new_image_path);
        }
        
        // Ensure stats structure exists
        if (!isset($new_data['about']['stats']) || !is_array($new_data['about']['stats'])) {
            $new_data['about']['stats'] = array(
                'years' => '10+',
                'shows' => '500+',
                'countries' => '20+',
                'festivals' => '50+'
            );
        }
    } else {
        // Default values if not set
        $new_data['about'] = array(
            'title' => 'ABOUT DJ JEFF BONE',
            'description' => '',
            'image' => '',
            'stats' => array(
                'years' => '10+',
                'shows' => '500+',
                'countries' => '20+',
                'festivals' => '50+'
            )
        );
        error_log("About section not found in POST data, using default");
    }
    
    // Process Events section
    $new_data['events'] = array();
    if (isset($_POST['events']) && is_array($_POST['events'])) {
        foreach ($_POST['events'] as $index => $event) {
            // Ensure required fields exist
            if (!empty($event['venue']) && !empty($event['date']) && !empty($event['location'])) {
                // Generate an ID if one doesn't exist
                if (empty($event['id'])) {
                    $event['id'] = time() . rand(1000, 9999);
                }
                
                // Handle event image upload
                if (isset($event['image'])) {
                    $old_image_path = $event['image'];
                    $new_image_path = handleFileUpload("event_image_{$index}", "delete_event_image_{$index}", $old_image_path);
                    $event['image'] = $new_image_path;
                    error_log("Event image path saved as: " . $new_image_path);
                } else {
                    $event['image'] = '';
                }
                
                $new_data['events'][] = $event;
            }
        }
    } else {
        error_log("Events section not found in POST data or not an array");
    }
    
    // Process Music section
    $new_data['music'] = array();
    if (isset($_POST['music']) && is_array($_POST['music'])) {
        foreach ($_POST['music'] as $track) {
            // Ensure required fields exist
            if (!empty($track['title']) && !empty($track['embed'])) {
                // Generate an ID if one doesn't exist
                if (empty($track['id'])) {
                    $track['id'] = time() . rand(1000, 9999);
                }
                
                $new_data['music'][] = $track;
            }
        }
    } else {
        error_log("Music section not found in POST data or not an array");
    }
    
    // Process Videos section
    $new_data['videos'] = array();
    if (isset($_POST['videos']) && is_array($_POST['videos'])) {
        foreach ($_POST['videos'] as $video) {
            // Ensure required fields exist
            if (!empty($video['title']) && !empty($video['embed'])) {
                // Generate an ID if one doesn't exist
                if (empty($video['id'])) {
                    $video['id'] = time() . rand(1000, 9999);
                }
                
                $new_data['videos'][] = $video;
            }
        }
    } else {
        error_log("Videos section not found in POST data or not an array");
    }
    
    // Process Gallery section
    $new_data['gallery'] = array();
    if (isset($_POST['gallery']) && is_array($_POST['gallery'])) {
        foreach ($_POST['gallery'] as $index => $image) {
            // Handle gallery image upload
            if (isset($image['src'])) {
                $old_image_path = $image['src'];
                $new_image_path = handleFileUpload("gallery_image_{$index}", "delete_gallery_image_{$index}", $old_image_path);
                $image['src'] = $new_image_path;
                error_log("Gallery image path saved as: " . $new_image_path);
            } else {
                $image['src'] = '';
            }
            
            // Ensure there's at least a source path or alt text
            if (!empty($image['src'])) {
                // Generate an ID if one doesn't exist
                if (empty($image['id'])) {
                    $image['id'] = time() . rand(1000, 9999);
                }
                
                $new_data['gallery'][] = $image;
            }
        }
    } else {
        error_log("Gallery section not found in POST data or not an array");
    }
    
    // Process Contact section - still save email and phone in data structure
    // but we won't display them on the frontend
    if (isset($_POST['contact'])) {
        $new_data['contact'] = $_POST['contact'];
    } else {
        // Default values if not set
        $new_data['contact'] = array(
            'email' => '',
            'phone' => '',
            'socials' => array(
                'instagram' => '',
                'facebook' => '',
                'twitter' => '',
                'soundcloud' => ''
            )
        );
        error_log("Contact section not found in POST data, using default");
    }
    
    // Debug - log what we're trying to save
    error_log("Attempting to save data to: " . $data_file);
    
    // Save the data to the JSON file with error handling
    $json_data = json_encode($new_data, JSON_PRETTY_PRINT);
    if ($json_data === false) {
        error_log("JSON encoding failed: " . json_last_error_msg());
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Failed to encode data: ' . json_last_error_msg()
        );
        header('Location: index.php?admin=true');
        exit;
    }
    
    // Use file_put_contents with proper error handling
    $save_result = @file_put_contents($data_file, $json_data);
    
    if ($save_result !== false) {
        error_log("Data saved successfully: " . $save_result . " bytes written");
        // Set more permissive permissions for the file (readable by web server and group)
        @chmod($data_file, 0644);
        error_log("File permissions set to 0644");
        
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'Content updated successfully!'
        );
    } else {
        $error = error_get_last();
        error_log("Failed to save data to: " . $data_file);
        error_log("Error details: " . ($error ? $error['message'] : 'Unknown error'));
        
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Failed to save changes. Error: ' . ($error ? $error['message'] : 'Unknown file permission error')
        );
    }
    
    // Redirect back to admin panel
    header('Location: index.php?admin=true');
    exit;
} else {
    // Redirect to admin if accessed directly
    header('Location: index.php?admin=true');
    exit;
}
?>